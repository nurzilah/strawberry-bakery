<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Cek apakah sudah pernah order produk ini
        $hasOrdered = OrderItem::whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'completed');
            })
            ->where('product_id', $product->id)
            ->exists();

        if (!$hasOrdered) {
            return back()->with('error', 'Anda harus membeli produk ini dan pesanan berstatus selesai terlebih dahulu sebelum memberikan ulasan.');
        }

        // Cek apakah sudah memberikan review
        $existingReview = ProductReview::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        ProductReview::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        // Update rata-rata rating di tabel product
        $this->updateProductRating($product);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    public function destroy(ProductReview $review)
    {
        if ($review->user_id !== auth()->id() && auth()->user()->role !== 'owner' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $product = $review->product;
        $review->delete();

        // Update rata-rata rating di tabel product
        $this->updateProductRating($product);

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    private function updateProductRating(Product $product)
    {
        $avg = ProductReview::where('product_id', $product->id)->where('is_approved', true)->avg('rating');
        $count = ProductReview::where('product_id', $product->id)->where('is_approved', true)->count();

        $product->update([
            'rating' => $avg ?? 0,
            'reviews_count' => $count
        ]);
    }
}
