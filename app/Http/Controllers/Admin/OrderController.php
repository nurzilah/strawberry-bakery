<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\PaymentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(15)->withQueryString();

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
            return view('kasir.orders.index', compact('orders'));
        }

        return view('admin.orders.index', [
            'orders' => $orders,
            'confirmRoute' => 'admin.orders.confirm',
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        
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
        
        return view('admin.orders.show', compact('order'));
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

            $productIds = $lockedOrder->items
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->values();

            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($lockedOrder->items as $item) {
                $product = $products->get($item->product_id);

                if (!$product) {
                    return [
                        'success' => false,
                        'message' => "Produk {$item->product_name} tidak ditemukan.",
                    ];
                }

                if ((int) $product->stock < (int) $item->quantity) {
                    return [
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi untuk konfirmasi pesanan.",
                    ];
                }
            }

            foreach ($lockedOrder->items as $item) {
                Product::whereKey($item->product_id)->update([
                    'stock' => DB::raw('stock - ' . (int) $item->quantity),
                    'purchase_count' => DB::raw('purchase_count + ' . (int) $item->quantity),
                ]);
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

        if ($result['order']->user) {
            $result['order']->user->notify(new PaymentConfirmed($result['order']));
        }

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

        // Return stock if cancelled after being processed
        if ($order->status === 'processing' && $targetStatus === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    \App\Models\Product::whereKey($item->product_id)->update([
                        'stock' => DB::raw('stock + ' . (int) $item->quantity),
                        'purchase_count' => DB::raw('GREATEST(0, purchase_count - ' . (int) $item->quantity . ')')
                    ]);
                }
            }
        }

        $order->update(['status' => $targetStatus]);

        $message = $targetStatus === 'completed'
            ? 'Pesanan selesai diambil.'
            : 'Status pesanan berhasil diperbarui.';

        return redirect()->back()->with('success', $message);
    }
}
