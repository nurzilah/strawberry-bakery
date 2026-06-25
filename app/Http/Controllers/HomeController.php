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

        if (auth()->check()) {
            $recommendationService = app(\App\Services\RecommendationService::class);
            $recommendedProducts = $recommendationService->getRecommendations(auth()->id(), 5, 4);
            $isRecommendation = $recommendationService->hasPurchaseHistory(auth()->id());
        } else {
            // Jika belum login, ambil produk populer sebagai fallback
            $recommendedProducts = Product::with('category')
                ->where('is_available', true)
                ->orderBy('purchase_count', 'desc')
                ->take(4)
                ->get();
        }

        return view('home', compact('products', 'recommendedProducts', 'isRecommendation'));
    }
}
