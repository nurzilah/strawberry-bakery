<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_available', true);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter berdasarkan harga
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter berdasarkan rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sorting
        $sortBy = $request->input('sort', 'popular');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
            default:
                $query->orderBy('reviews_count', 'desc');
                break;
        }

        // Pagination
        $perPage = 12;
        $paginatedProducts = $query->paginate($perPage)->withQueryString();

        // Data for sidebar
        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_available', true);
        }])->get()->map(function($cat) {
            return [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'count' => $cat->products_count
            ];
        })->toArray();

        // Add 'all' category to the beginning
        $totalAvailableProducts = Product::where('is_available', true)->count();
        array_unshift($categories, [
            'name' => 'Semua Produk',
            'slug' => 'all',
            'count' => $totalAvailableProducts
        ]);

        $ratings = [
            ['stars' => 5, 'count' => Product::where('rating', '>=', 5)->count()],
            ['stars' => 4, 'count' => Product::where('rating', '>=', 4)->count()],
            ['stars' => 3, 'count' => Product::where('rating', '>=', 3)->count()],
        ];

        $recommendations = [];
        $hasPurchaseHistory = false;
        
        if (auth()->check()) {
            $recommendationService = app(RecommendationService::class);
            $hasPurchaseHistory = $recommendationService->hasPurchaseHistory(auth()->id());
            
            if ($hasPurchaseHistory) {
                $recommendations = $recommendationService->getRecommendations(auth()->id(), 6);
            }
        }

        return view('products.index', compact(
            'paginatedProducts',
            'categories',
            'ratings',
            'totalAvailableProducts',
            'recommendations'
        ));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'reviews' => function($q) {
            $q->where('is_approved', true)->with('user')->latest();
        }])->where('slug', $slug)->firstOrFail();

        $hasOrdered = false;
        $userReview = null;

        if (auth()->check()) {
            $userId = auth()->id();
            $hasOrdered = \App\Models\OrderItem::whereHas('order', function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('status', 'completed');
                })
                ->where('product_id', $product->id)
                ->exists();
                
            $userReview = $product->reviews->where('user_id', $userId)->first();
        }

        return view('katalog.detail', compact('product', 'hasOrdered', 'userReview'));
    }
}
