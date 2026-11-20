<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\StockHistory;
use App\Services\ReceiptDeliveryService;
use App\Services\PackageReceiptGrouper;
use App\Services\CourierNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'courier', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(15)->withQueryString();
        $couriers = User::where('role', 'kurir')->orderBy('name')->get();

        // Sync status with Midtrans API if pending
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            foreach ($orders as $order) {
                if ($order->payment_status === 'pending' && $order->midtrans_order_id) {
                    try {
                        $status = \Midtrans\Transaction::status($order->midtrans_order_id);
                        $transactionStatus = $status->transaction_status;
                        
                        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
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
                        // Ignore
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore Config error
        }

        if (request()->routeIs('kasir.*')) {
            return view('kasir.orders.index', compact('orders', 'couriers'));
        }

        return view('admin.orders.index', [
            'orders' => $orders,
            'couriers' => $couriers,
            'confirmRoute' => 'admin.orders.confirm',
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'courier', 'items.product', 'packages.items.product']);
        $couriers = User::where('role', 'kurir')->orderBy('name')->get();
        
        // Sync status with Midtrans API if pending
        if ($order->payment_status === 'pending' && $order->midtrans_order_id) {
            try {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                
                $status = \Midtrans\Transaction::status($order->midtrans_order_id);
                $transactionStatus = $status->transaction_status;
                
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
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
                // Biarkan saja jika error/belum ada di Midtrans
            }
        }
        
        return view('admin.orders.show', compact('order', 'couriers'));
    }

    public function assignCourier(Request $request, Order $order, CourierNotificationService $notificationService)
    {
        $request->validate([
            'courier_id' => 'nullable',
            'courier_name' => 'nullable|string|max:255',
            'courier_phone' => 'nullable|string|max:20',
            'delivery_status' => 'nullable|in:menunggu_kurir,dalam_pengantaran,terkirim',
        ]);

        $previousDeliveryStatus = $order->delivery_status;
        $courier = null;

        if ($request->courier_id && $request->courier_id !== 'external') {
            $courier = User::where('role', 'kurir')->findOrFail($request->courier_id);
            $targetDeliveryStatus = $request->input('delivery_status', 'dalam_pengantaran');
            $updateData = [
                'courier_id' => $courier->id,
                'courier_name' => $courier->name,
                'courier_phone' => $courier->phone,
                'delivery_status' => $targetDeliveryStatus,
            ];
            $courierLabel = $courier->name;
        } elseif ($request->courier_id === 'external' || filled($request->courier_name)) {
            $targetDeliveryStatus = $request->input('delivery_status', 'dalam_pengantaran');
            $updateData = [
                'courier_id' => null,
                'courier_name' => $request->courier_name ?: 'Kurir Eksternal / Ojek Online',
                'courier_phone' => $request->courier_phone,
                'delivery_status' => $targetDeliveryStatus,
            ];
            $courierLabel = $updateData['courier_name'];
        } else {
            $targetDeliveryStatus = $request->input('delivery_status', 'menunggu_kurir');
            $updateData = [
                'courier_id' => null,
                'courier_name' => null,
                'courier_phone' => null,
                'delivery_status' => $targetDeliveryStatus,
            ];
            $courierLabel = 'Belum Ditugaskan';
        }

        // Sinkronisasi status utama pesanan & delivered_at
        if ($targetDeliveryStatus === 'dalam_pengantaran') {
            if (in_array($order->status, ['pending', 'waiting_verification'], true)) {
                $updateData['status'] = 'processing';
            }
            $updateData['delivered_at'] = null;
        } elseif ($targetDeliveryStatus === 'terkirim') {
            $updateData['status'] = 'completed';
            $updateData['delivered_at'] = now();
        } else {
            // Menunggu kurir
            $updateData['delivered_at'] = null;
        }

        $order->update($updateData);

        // Notifikasi WhatsApp
        if ($targetDeliveryStatus === 'dalam_pengantaran') {
            // 1. Kirim notifikasi WA ke kurir internal dengan link Google Maps
            if ($courier) {
                $notificationService->sendAssignmentNotificationToCourier($order, $courier);
            }
            // 2. Kirim notifikasi WA ke pelanggan
            if ($previousDeliveryStatus !== 'dalam_pengantaran') {
                $notificationService->sendStatusUpdateNotification($order, 'dalam_pengantaran');
            }
        } elseif ($targetDeliveryStatus === 'terkirim' && $previousDeliveryStatus !== 'terkirim') {
            $notificationService->sendStatusUpdateNotification($order, 'terkirim');
        }

        $statusText = [
            'menunggu_kurir' => 'Menunggu Kurir',
            'dalam_pengantaran' => 'Dalam Pengantaran',
            'terkirim' => 'Terkirim',
        ][$targetDeliveryStatus] ?? $targetDeliveryStatus;

        return redirect()->back()->with('success', "Informasi penugasan kurir ({$courierLabel}) dan status pengantaran ({$statusText}) berhasil diperbarui.");
    }

    public function confirm(Order $order)
    {
        $result = DB::transaction(function () use ($order) {
            $lockedOrder = Order::with('items')
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status !== 'waiting_verification') {
                return [
                    'success' => false,
                    'message' => 'Pesanan ini tidak berada dalam status menunggu verifikasi.',
                ];
            }

            $updateData = ['status' => 'processing'];

            if (Schema::hasColumn('orders', 'payment_status')) {
                $updateData['payment_status'] = 'success';
            }

            $lockedOrder->forceFill($updateData)->save();

            return [
                'success' => true,
                'order' => $lockedOrder->fresh('user'),
            ];
        });

        if (!$result['success']) {
            return redirect()->back()->withErrors([
                'status' => $result['message'],
            ]);
        }

        app(ReceiptDeliveryService::class)->sendOnce($result['order']);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting_verification,processing,completed,cancelled',
        ]);

        $targetStatus = $request->status;

        if (auth()->user()->role === 'kasir') {
            if ($order->status !== 'processing' || $targetStatus !== 'completed') {
                return redirect()->back()->withErrors([
                    'status' => 'Kasir hanya dapat mengubah status pesanan dari processing menjadi completed.',
                ]);
            }
        }

        $allowedTransitions = [
            'pending' => ['waiting_verification', 'cancelled'],
            'waiting_verification' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($targetStatus, $allowedTransitions[$order->status] ?? [], true)) {
            return redirect()->back()->withErrors([
                'status' => 'Perubahan status tidak valid untuk kondisi pesanan saat ini.',
            ]);
        }

        // Return stock if cancelled
        if ($order->status !== 'cancelled' && $targetStatus === 'cancelled') {
            $adminId = auth()->id() ?? $order->user_id;

            // 1. Restock regular items
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $stockBefore = (int) $product->stock;
                        $qty = (int) $item->quantity;
                        $product->increment('stock', $qty);
                        $product->update([
                            'purchase_count' => DB::raw('GREATEST(0, purchase_count - ' . $qty . ')')
                        ]);

                        StockHistory::create([
                            'product_id' => $product->id,
                            'user_id' => $adminId,
                            'quantity_added' => $qty,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockBefore + $qty,
                            'notes' => "Pengembalian stok: Pembatalan pesanan #{$order->id} (Item Reguler)",
                        ]);
                    }
                }
            }

            // 2. Restock package items (Custom Box)
            $order->loadMissing('packages.items.product');
            foreach ($order->packages as $package) {
                foreach ($package->items as $item) {
                    if ($item->product) {
                        $product = $item->product;
                        $stockBefore = (int) $product->stock;
                        $qty = (int) $item->quantity;
                        $product->increment('stock', $qty);
                        $product->update([
                            'purchase_count' => DB::raw('GREATEST(0, purchase_count - ' . $qty . ')')
                        ]);

                        StockHistory::create([
                            'product_id' => $product->id,
                            'user_id' => $adminId,
                            'quantity_added' => $qty,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockBefore + $qty,
                            'notes' => "Pengembalian stok: Pembatalan pesanan #{$order->id} (Paket Custom Box)",
                        ]);
                    }
                }
            }
        }

        $updateData = ['status' => $targetStatus];

        if ($targetStatus === 'completed') {
            if ($order->delivery_method === 'delivery') {
                $updateData['delivery_status'] = 'terkirim';
                $updateData['delivered_at'] = now();
                $message = 'Pesanan selesai diantar.';
            } else {
                $message = 'Pesanan selesai diambil.';
            }
        } else {
            $message = 'Status pesanan berhasil diperbarui.';
        }

        $order->update($updateData);

        // Jika pesanan delivery selesai diantar, kirim notifikasi WA ke pelanggan
        if ($targetStatus === 'completed' && $order->delivery_method === 'delivery') {
            app(CourierNotificationService::class)->sendStatusUpdateNotification($order, 'terkirim');
        }

        return redirect()->back()->with('success', $message);
    }

    public function printReceipt($id)
    {
        $order = Order::with(['items', 'packages.items.product'])->findOrFail($id);
        $packageGroups = app(PackageReceiptGrouper::class)->group($order->packages);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt_pdf', compact('order', 'packageGroups'));
        $pdf->setPaper(array(0, 0, 226.77, 800), 'portrait');
        
        return $pdf->stream('Struk_Pesanan_' . $order->id . '.pdf');
    }
}
