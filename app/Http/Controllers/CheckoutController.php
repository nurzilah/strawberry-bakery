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
            'payment_method' => $request->payment_method ?? 'midtrans'
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

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Gagal memproses pembayaran. ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $order = Order::where('midtrans_order_id', $request->order_id)->first();
            
            if ($order) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                } else if ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                    $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                } else if ($request->transaction_status == 'pending') {
                    $order->update(['payment_status' => 'pending']);
                }
            }
        }
        
        return response()->json(['status' => 'ok']);
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function pending()
    {
        return view('checkout.pending');
    }

    public function failed()
    {
        return view('checkout.failed');
    }
}
