<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->latest()
            ->take(8)
            ->get();

        $recommendedProducts = collect();
        $isRecommendation = false;
        $hasOrders = false;

        if (auth()->check()) {
            $hasOrders = \App\Models\Order::where('user_id', auth()->id())->where('status', 'completed')->exists();
        }

        if (auth()->check() && $hasOrders) {
            // Kondisi 3 — Sudah login DAN sudah pernah order (ada history)
            $recommendationService = app(\App\Services\RecommendationService::class);
            $recommendedProducts = $recommendationService->getRecommendations(auth()->id(), 5, 4);
            $isRecommendation = true;
        } else {
            // Kondisi 1 & 2 — Belum login (guest) ATAU Sudah login TAPI belum pernah order (customer baru)
            $recommendedProducts = Product::with('category')
                ->withCount('orderItems')
                ->where('is_available', true)
                ->orderByDesc('order_items_count')
                ->take(4)
                ->get();
            $isRecommendation = false;
        }

        $totalReviews = \App\Models\ProductReview::where('is_approved', true)->count();
        $averageRating = \App\Models\ProductReview::where('is_approved', true)->avg('rating') ?? 5.0;
        $latestReviews = \App\Models\ProductReview::with('user')->where('is_approved', true)->latest()->take(3)->get();
        $totalProducts = \App\Models\Product::where('is_available', true)->count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();

        return view('home', compact('products', 'recommendedProducts', 'isRecommendation', 'hasOrders', 'totalReviews', 'averageRating', 'latestReviews', 'totalProducts', 'totalCustomers'));
    }
}
