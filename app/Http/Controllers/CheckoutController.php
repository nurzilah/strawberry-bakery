<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Services\ReceiptDeliveryService;

class CheckoutController extends Controller
{
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
        $user = auth()->user();
        $carts = $user->carts;
        
        $total = 0;
        foreach ($carts as $cart) {
            $product = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id') 
                ? $cart->product 
                : $cart->detailCart->product;
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;
            
            $total += $product->price * $qty;
        }

        $vouchers = \App\Models\Voucher::where('is_active', true)->get();
        $isFirstOrder = !\App\Models\Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        $totalQty = 0;
        foreach ($carts as $cart) {
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;
            $totalQty += $qty;
        }
        $promoService = app(\App\Services\PromoCalculatorService::class);
        $orderPromo = $promoService->calculate('order', $totalQty, $total);

        return view('checkout.index', compact('carts', 'total', 'totalQty', 'vouchers', 'isFirstOrder', 'orderPromo'));
    }

    public function process(Request $request)
    {
        $user = auth()->user();
        $carts = $user->carts;
        
        if ($carts->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning("Checkout failed: Cart is empty for user " . $user->id);
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang kosong'
            ], 400);
        }

        $total = 0;
        foreach ($carts as $cart) {
            $product = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id') 
                ? $cart->product 
                : $cart->detailCart->product;
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;
            
            if ((int) $product->stock < (int) $qty) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok produk {$product->name} tidak mencukupi. Sisa stok: {$product->stock}."
                    ], 400);
                }
                return redirect()->back()->with('error', "Stok produk {$product->name} tidak mencukupi.");
            }
            
            $total += $product->price * $qty;
        }

        // Total qty for delivery validation
        $totalQty = 0;
        foreach ($carts as $cart) {
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;
            $totalQty += $qty;
        }

        $minDeliveryQty = config('store.delivery_min_qty', 12);
        $deliveryMethod = $request->delivery_method === 'delivery' ? 'delivery' : 'pickup';
        
        if ($deliveryMethod === 'delivery' && $totalQty < $minDeliveryQty) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Delivery hanya tersedia untuk pemesanan minimal ' . $minDeliveryQty . ' item.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Delivery hanya tersedia untuk pemesanan minimal ' . $minDeliveryQty . ' item.');
        }

        // Validate requested date
        $request->validate([
            'requested_date' => 'required|date|after_or_equal:today|before_or_equal:+2 days',
        ], [
            'requested_date.after_or_equal' => 'Tanggal pengambilan/pengantaran tidak boleh di masa lalu.',
            'requested_date.before_or_equal' => 'Tanggal pengambilan/pengantaran maksimal H+2 (2 hari ke depan).',
        ]);

        $requestedDate = $request->requested_date ?? \Carbon\Carbon::today()->format('Y-m-d');
        $minPickupDate = \Carbon\Carbon::today()->format('Y-m-d');
        $maxPickupDate = \Carbon\Carbon::today()->addDays(2)->format('Y-m-d');

        if ($requestedDate < $minPickupDate) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal pengambilan/pengantaran tidak boleh di masa lalu.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tanggal pengambilan/pengantaran tidak boleh di masa lalu.');
        }

        if ($requestedDate > $maxPickupDate) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal pengambilan/pengantaran maksimal H+2 (2 hari ke depan).'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tanggal pengambilan/pengantaran maksimal H+2 (2 hari ke depan).');
        }

        // Validate requested time
        $requestedTime = $request->requested_time;
        if (! $requestedTime || ! \App\Helpers\StoreHoursHelper::isValidTimeSlot($requestedDate, $requestedTime)) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jam yang Anda pilih tidak valid atau sudah lewat untuk tanggal tersebut.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Jam yang Anda pilih tidak valid atau sudah lewat untuk tanggal tersebut.');
        }


        // Validate pickup disclaimer checkbox (only for pickup orders)
        if ($deliveryMethod === 'pickup') {
            if (! $request->boolean('pickup_disclaimer_agreed')) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda wajib menyetujui ketentuan pengambilan pesanan sebelum checkout.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Anda wajib menyetujui ketentuan pengambilan pesanan sebelum checkout.');
            }
        }

        // Hitung pickup deadline (hanya untuk pickup)
        $pickupDeadlineAt = null;
        if ($deliveryMethod === 'pickup') {
            $deadlineHours = config('store.pickup_deadline_hours', 2);
            $pickupDeadlineAt = \Carbon\Carbon::parse("{$requestedDate} {$requestedTime}")
                ->addHours($deadlineHours);
        }

        // Hitung promo
        $promoService = app(\App\Services\PromoCalculatorService::class);
        $promoResult = $promoService->calculate('order', $totalQty, $total);
        
        $discountAmount = $promoResult['discount_amount'];
        $freeQtyApplied = $promoResult['free_qty'];
        
        // Shipping & Location calculation logic
        $shippingCost = 0;
        $distanceKm = null;
        $deliveryLat = null;
        $deliveryLng = null;

        if ($deliveryMethod === 'delivery') {
            $request->validate([
                'delivery_address' => 'required|string',
                'delivery_lat'     => 'required|numeric',
                'delivery_lng'     => 'required|numeric',
            ], [
                'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
                'delivery_lat.required'     => 'Titik lokasi pengiriman pada peta wajib ditentukan.',
                'delivery_lng.required'     => 'Titik lokasi pengiriman pada peta wajib ditentukan.',
            ]);

            $deliveryLat = (float) $request->delivery_lat;
            $deliveryLng = (float) $request->delivery_lng;

            $shippingService = app(\App\Services\ShippingCalculatorService::class);
            $distanceKm = $shippingService->calculateDistance($deliveryLat, $deliveryLng);

            if (!$shippingService->isWithinRadius($distanceKm)) {
                $maxRadius = config('store.max_delivery_radius_km', 25.0);
                $msg = "Lokasi pengiriman ({$distanceKm} km) di luar batas jangkauan pengantaran toko (maksimal {$maxRadius} km). Silakan pilih metode Ambil di Toko.";
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => $msg], 422);
                }
                return redirect()->back()->with('error', $msg);
            }

            $shippingCost = $shippingService->calculateCost($distanceKm);
        }

        // Voucher logic
        $voucherCode = $request->input('voucher_code');
        $voucherDiscount = 0;
        $appliedVoucherCode = null;

        if (!empty($voucherCode)) {
            $voucher = \App\Models\Voucher::where('code', $voucherCode)->where('is_active', true)->first();
            if (!$voucher) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kode voucher tidak valid atau sudah tidak aktif.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Kode voucher tidak valid atau sudah tidak aktif.');
            }

            $eligibility = $voucher->checkEligibility($user, $total, $deliveryMethod, $distanceKm);
            if (!$eligibility['eligible']) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $eligibility['message']
                    ], 422);
                }
                return redirect()->back()->with('error', $eligibility['message']);
            }

            $voucherDiscount = $voucher->calculateDiscount($total, $shippingCost, $user, $deliveryMethod, $distanceKm);
            $appliedVoucherCode = $voucher->code;
        }
        
        $finalTotal = max(0, $total - $discountAmount + $shippingCost - $voucherDiscount);

        // Create Order
        $order = \App\Models\Order::create([
            'user_id'            => $user->id,
            'total_amount'       => $finalTotal,
            'status'             => 'pending',
            'payment_status'     => 'pending',
            'payment_method'     => $request->payment_method ?? 'midtrans',
            'requested_date'     => $requestedDate,
            'requested_time'     => $requestedTime,
            'pickup_deadline_at' => $pickupDeadlineAt,
            'delivery_method'    => $deliveryMethod,
            'delivery_address'   => $deliveryMethod === 'delivery' ? $request->delivery_address : null,
            'delivery_lat'       => $deliveryLat,
            'delivery_lng'       => $deliveryLng,
            'distance_km'        => $distanceKm,
            'shipping_cost'      => $shippingCost,
            'voucher_code'       => $appliedVoucherCode,
            'voucher_discount'   => $voucherDiscount,
            'discount_amount'    => $discountAmount,
            'free_qty_applied'   => $freeQtyApplied,
        ]);

        // Create Order Items
        foreach ($carts as $cart) {
            $product = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id') 
                ? $cart->product 
                : $cart->detailCart->product;
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'subtotal' => $product->price * $qty
            ]);
            
            // Note: Stok TIDAK dikurangi di sini (dipindah ke MidtransNotificationController/status)
        }

        // Notifikasi untuk Admin/Owner
        \App\Models\Notification::create([
            'type' => 'order',
            'title' => 'Pesanan Baru',
            'message' => "Pesanan #{$order->id} dari {$user->name} sebesar Rp " . number_format($finalTotal, 0, ',', '.'),
            'data' => ['order_id' => $order->id]
        ]);

        // Set Midtrans params
        $params = array(
            'transaction_details' => array(
                'order_id' => 'TRX-' . $order->id . '-' . time(),
                'gross_amount' => $finalTotal,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
            )
        );

        try {
            // Jalankan request token ke Midtrans terlebih dahulu
            $snapToken = Snap::getSnapToken($params);
            
            $order->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $params['transaction_details']['order_id']
            ]);

            // PROSES CLEAR CART DIPINDAHKAN DI SINI (Dijalankan hanya saat pembuatan token SUKSES)
            foreach ($carts as $cart) {
                $detailCart = $cart->detailCart; 
                
                if (method_exists($cart, 'items')) {
                    $cart->items()->delete();
                }
                $cart->delete();
                
                if ($detailCart) {
                    $detailCart->delete();
                }
            }

            if ($request->ajax()) {
                \Illuminate\Support\Facades\Log::info("Checkout Success: returning snap token for order " . $order->id);
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('checkout.status', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Midtrans Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses pembayaran. ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('checkout.index')->with('error', 'Gagal memproses pembayaran. ' . $e->getMessage());
        }
    }

    public function status(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Sync status with Midtrans API if pending
        if ($order->payment_status === 'pending' && $order->midtrans_order_id) {
            try {
                $status = \Midtrans\Transaction::status($order->midtrans_order_id);
                $transactionStatus = $status->transaction_status;
                
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    if ($order->payment_status !== 'paid') {
                        // Kurangi stok reguler
                        foreach ($order->items as $item) {
                            if ($item->product_id) {
                                $product = \App\Models\Product::find($item->product_id);
                                if ($product) {
                                    $product->decrement('stock', (int) $item->quantity);
                                    $product->increment('purchase_count', (int) $item->quantity);
                                }
                            }
                        }
                        
                        // Kurangi stok paket
                        app(\App\Services\PackageOrderService::class)->decrementStock($order);
                    }

                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'waiting_verification',
                        'paid_at' => now(),
                        'payment_type' => $status->payment_type ?? $order->payment_type,
                        'midtrans_transaction_id' => $status->transaction_id ?? $order->midtrans_transaction_id
                    ]);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                    $order->update([
                        'payment_status' => $transactionStatus === 'expire' ? 'expired' : ($transactionStatus === 'cancel' ? 'cancelled' : 'failed'),
                        'status' => 'cancelled'
                    ]);
                }
            } catch (\Exception $e) {
                // Biarkan lolos jika gagal
            }
        }

        if ($order->payment_status === 'paid') {
            app(ReceiptDeliveryService::class)->sendOnce($order);
        }

        return view('checkout.status', compact('order'));
    }
}
