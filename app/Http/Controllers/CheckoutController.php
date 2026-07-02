<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
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

        return view('checkout.index', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        $user = auth()->user();
        $carts = $user->carts;
        
        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

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

        // Create Order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method ?? 'midtrans',
            'pickup_date' => $request->pickup_date ?? \Carbon\Carbon::now()->addDays(2)->format('Y-m-d')
        ]);

        // Clear cart
        foreach ($carts as $cart) {
            $detailCart = $cart->detailCart; // Simpan referensi ke parent (detail_carts)
            
            // Hapus child (carts) terlebih dahulu agar tidak melanggar foreign key constraint
            if (method_exists($cart, 'items')) {
                $cart->items()->delete();
            }
            $cart->delete();
            
            // Setelah child dihapus, aman untuk menghapus parent (detail_carts)
            if ($detailCart) {
                $detailCart->delete();
            }
        }

        // Notifikasi untuk Admin/Owner
        \App\Models\Notification::create([
            'type' => 'order',
            'title' => 'Pesanan Baru',
            'message' => "Pesanan #{$order->id} dari {$user->name} sebesar Rp " . number_format($total, 0, ',', '.'),
            'data' => ['order_id' => $order->id]
        ]);

        // Set Midtrans params
        $params = array(
            'transaction_details' => array(
                'order_id' => 'TRX-' . $order->id . '-' . time(),
                'gross_amount' => $total,
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

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('checkout.status', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
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
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Sync status with Midtrans API if pending
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
                // Biarkan saja jika error/belum ada di Midtrans
            }
        }

        return view('checkout.status', compact('order'));
    }
}
