<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
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
            'payment_method' => $request->payment_method ?? 'transfer'
        ]);

        // Clear cart
        foreach ($carts as $cart) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id')) {
                $cart->detailCart?->delete();
            }
            $cart->delete();
        }

        // Notifikasi untuk Admin/Owner
        \App\Models\Notification::create([
            'type' => 'order',
            'title' => 'Pesanan Baru',
            'message' => "Pesanan #{$order->id} dari {$user->name} sebesar Rp " . number_format($total, 0, ',', '.'),
            'data' => ['order_id' => $order->id]
        ]);

        return redirect()->route('home')->with('success', 'Checkout berhasil');
    }
}
