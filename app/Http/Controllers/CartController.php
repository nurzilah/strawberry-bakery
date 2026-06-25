<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailCart;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function index()
    {
        $relations = Schema::hasColumn('carts', 'product_id')
            ? ['product.category']
            : ['detailCart.product.category'];

        $carts = auth()->user()->carts()->with($relations)->get();
        
        $recommendations = auth()->check()
            ? app(RecommendationService::class)->getRecommendations(auth()->id(), 4)
            : collect();

        return view('cart.index', compact('carts', 'recommendations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if (Schema::hasColumn('carts', 'product_id')) {
            $cart = Cart::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cart) {
                $cart->update(['quantity' => $cart->quantity + $request->quantity]);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        $cart = Cart::query()
            ->where('user_id', auth()->id())
            ->whereHas('detailCart', fn ($query) => $query->where('product_id', $request->product_id))
            ->with('detailCart')
            ->first();

        if ($cart && $cart->detailCart) {
            $cart->detailCart->update([
                'quantity' => $cart->detailCart->quantity + $request->quantity,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        $detailCart = DetailCart::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        Cart::create([
            'user_id' => auth()->id(),
            'detail_cart_id' => $detailCart->id,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $request->validate(['quantity' => 'required|integer|min:1']);

        if (Schema::hasColumn('carts', 'quantity')) {
            $cart->update(['quantity' => $request->quantity]);
        } else {
            $cart->loadMissing('detailCart');
            $cart->detailCart?->update(['quantity' => $request->quantity]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);
        $cart->loadMissing('detailCart');
        $detailCart = $cart->detailCart;

        $cart->delete();
        $detailCart?->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
