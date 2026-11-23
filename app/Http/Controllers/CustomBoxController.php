<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\PromoRule;
use App\Services\PackageOrderService;
use Midtrans\Config;
use Midtrans\Snap;

class CustomBoxController extends Controller
{
    private const MAX_CUSTOM_BOX_PRODUCT_PRICE = 12000;

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => []
        ];
    }

    public function index()
    {
        // Ambil produk yang stoknya ada untuk dipilih ke dalam box
        $products = Product::where('stock', '>', 0)
            ->where('price', '<', self::MAX_CUSTOM_BOX_PRODUCT_PRICE)
            ->get();
        $packagePromos = PromoRule::where('scope', 'package')
            ->where('is_active', true)
            ->orderByDesc('min_qty')
            ->get();

        $vouchers = \App\Models\Voucher::where('is_active', true)->get();
        $isFirstOrder = auth()->check() ? !\App\Models\Order::where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->exists() : false;

        return view('custom-box.index', compact('products', 'packagePromos', 'vouchers', 'isFirstOrder'));
    }

    public function checkout(Request $request, PackageOrderService $packageService)
    {
        $user = auth()->user();

        $request->validate([
            'requested_date' => 'required|date|after_or_equal:today|before_or_equal:+2 days',
            'requested_time' => 'required|string',
            'delivery_method' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_method,delivery',
            'boxes' => 'required|array|min:1',
            'boxes.*.quantity' => 'required|integer|min:1',
            'boxes.*.beverage_type' => 'nullable|in:gelas,botol',
            'boxes.*.items' => 'required|array|min:1',
            'boxes.*.items.*.product_id' => 'required|exists:products,id',
            'boxes.*.items.*.quantity' => 'required|integer|min:1',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'voucher_code' => 'nullable|string',
        ], [
            'requested_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'requested_date.before_or_equal' => 'Tanggal pengambilan/pengantaran maksimal H+2 (2 hari ke depan).',
            'requested_time.required' => 'Jam pengambilan/pengantaran wajib dipilih.',
        ]);

        $boxes = $request->input('boxes', []);
        foreach ($boxes as $boxIndex => $box) {
            $selectedProductIds = [];
            foreach ($box['items'] as $item) {
                $productId = (string) $item['product_id'];
                $product = Product::find($productId);
                if (! $product || (float) $product->price >= self::MAX_CUSTOM_BOX_PRODUCT_PRICE) {
                    $productName = $product?->name ?? 'Produk tersebut';

                    return response()->json([
                        'status' => 'error',
                        'message' => "{$productName} tidak dapat dimasukkan ke snack box karena harga produk harus di bawah Rp 12.000.",
                    ], 422);
                }

                if (isset($selectedProductIds[$productId])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "{$productName} dipilih lebih dari sekali di Box ".((int) $boxIndex + 1).". Gunakan tombol +/- pada baris produk yang sudah ada.",
                    ], 422);
                }

                $selectedProductIds[$productId] = true;
            }

            if (count($selectedProductIds) < 3) {
                $selectedTypes = count($selectedProductIds);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Box '.((int) $boxIndex + 1)." baru berisi {$selectedTypes} jenis roti, minimal 3 jenis roti berbeda.",
                ], 422);
            }
        }

        $boxCount = collect($boxes)->sum(fn ($box) => (int) $box['quantity']);
        if ($boxCount < 10) {
            return response()->json([
                'status' => 'error',
                'message' => "Minimal pembelian 10 box, saat ini baru {$boxCount} box.",
            ], 422);
        }

        $requestedDate  = $request->input('requested_date');
        $requestedTime  = $request->input('requested_time');
        $deliveryMethod = $request->input('delivery_method');

        // Validasi jam operasional dan lead time
        if (!\App\Helpers\StoreHoursHelper::isValidTimeSlot($requestedDate, $requestedTime)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jam yang Anda pilih tidak valid atau sudah lewat untuk tanggal tersebut.'
            ], 400);
        }

        // Validasi disclaimer pickup
        if ($deliveryMethod === 'pickup' && !$request->boolean('pickup_disclaimer_agreed')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda wajib menyetujui ketentuan pengambilan pesanan sebelum checkout.'
            ], 400);
        }

        // Hitung deadline pickup jika pickup
        $pickupDeadlineAt = null;
        if ($deliveryMethod === 'pickup') {
            $deadlineHours = config('store.pickup_deadline_hours', 2);
            $pickupDeadlineAt = \Carbon\Carbon::parse("{$requestedDate} {$requestedTime}")
                ->addHours($deadlineHours);
        }

        // Validasi ketersediaan stok
        $requestedQuantities = [];
        foreach ($boxes as $box) {
            foreach ($box['items'] as $item) {
                $pid = $item['product_id'];
                if (!isset($requestedQuantities[$pid])) {
                    $requestedQuantities[$pid] = 0;
                }
                $requestedQuantities[$pid] += $item['quantity'] * $box['quantity'];
            }
        }

        foreach ($requestedQuantities as $pid => $qty) {
            $product = Product::find($pid);
            if ($product->stock < $qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Stok produk {$product->name} tidak mencukupi (sisa: {$product->stock}, diminta: {$qty})."
                ], 400);
            }
        }

        // Validasi dan kalkulasi lokasi & jarak pengantaran jika delivery
        $distanceKm = null;
        $deliveryLat = null;
        $deliveryLng = null;

        if ($deliveryMethod === 'delivery') {
            if (!$request->filled('delivery_lat') || !$request->filled('delivery_lng')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Titik lokasi pengiriman pada peta wajib ditentukan.'
                ], 422);
            }

            $deliveryLat = (float) $request->delivery_lat;
            $deliveryLng = (float) $request->delivery_lng;

            $shippingService = app(\App\Services\ShippingCalculatorService::class);
            $distanceKm = $shippingService->calculateDistance($deliveryLat, $deliveryLng);

            if (!$shippingService->isWithinRadius($distanceKm)) {
                $maxRadius = config('store.max_delivery_radius_km', 25.0);
                return response()->json([
                    'status' => 'error',
                    'message' => "Maaf, jarak pengiriman ({$distanceKm} km) melebihi batas maksimal {$maxRadius} km. Silakan pilih titik alamat yang lebih dekat atau gunakan metode Ambil di Toko (Pick Up)."
                ], 422);
            }
        }

        // Buat Order awal dengan total 0, akan diupdate oleh PackageOrderService
        $order = Order::create([
            'user_id'            => $user->id,
            'total_amount'       => 0,
            'status'             => 'pending',
            'payment_status'     => 'pending',
            'payment_method'     => 'midtrans',
            'requested_date'     => $requestedDate,
            'requested_time'     => $requestedTime,
            'pickup_deadline_at' => $pickupDeadlineAt,
            'delivery_method'    => $deliveryMethod,
            'delivery_address'   => $deliveryMethod === 'delivery' ? $request->delivery_address : null,
            'delivery_lat'       => $deliveryLat,
            'delivery_lng'       => $deliveryLng,
            'distance_km'        => $distanceKm,
            'shipping_cost'      => 0,
            'discount_amount'    => 0,
            'free_qty_applied'   => 0,
        ]);

        // Proses pembuatan package dan perhitungan promo & voucher
        $voucherCode = $request->input('voucher_code');
        try {
            $packageService->createPackagesForOrder($order, $boxes, $distanceKm, $voucherCode);
        } catch (\InvalidArgumentException $e) {
            $order->delete();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }

        // Ambil order yang sudah diupdate totalnya
        $order->refresh();

        // Notifikasi untuk Admin/Owner
        \App\Models\Notification::create([
            'type' => 'order',
            'title' => 'Pesanan Custom Box Baru',
            'message' => "Pesanan #{$order->id} (Paket) dari {$user->name} sebesar Rp " . number_format($order->total_amount, 0, ',', '.'),
            'data' => ['order_id' => $order->id]
        ]);

        // Request token ke Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => 'TRX-' . $order->id . '-' . time(),
                'gross_amount' => $order->total_amount,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
            )
        );

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $order->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $params['transaction_details']['order_id']
            ]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Midtrans Error (Custom Box): " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses pembayaran. ' . $e->getMessage()
            ], 500);
        }
    }
}
