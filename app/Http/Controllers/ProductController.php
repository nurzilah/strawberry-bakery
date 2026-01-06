<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Data produk (nanti bisa diganti dengan database)
        $products = [
            [
                'id' => 1,
                'name' => 'Croissant Butter Premium',
                'category' => 'Pastry',
                'price' => 15000,
                'rating' => 4.9,
                'reviews' => 128,
                'image' => 'croissant.jpg',
                'label' => 'Terlaris',
                'label_color' => 'red'
            ],
            [
                'id' => 2,
                'name' => 'Pain au Chocolat',
                'category' => 'Pastry',
                'price' => 16000,
                'rating' => 4.8,
                'reviews' => 99,
                'image' => 'pain-chocolat.jpg',
                'label' => 'Baru',
                'label_color' => 'red'
            ],
            [
                'id' => 3,
                'name' => 'Donut Coklat Premium',
                'category' => 'Roti Manis',
                'price' => 12000,
                'rating' => 4.7,
                'reviews' => 82,
                'image' => 'donut-coklat.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 4,
                'name' => 'Baguette Klasik',
                'category' => 'Roti Prancis',
                'price' => 20000,
                'rating' => 4.9,
                'reviews' => 110,
                'image' => 'baguette.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 5,
                'name' => 'Sourdough Bread',
                'category' => 'Roti Artisan',
                'price' => 25000,
                'rating' => 5.0,
                'reviews' => 67,
                'image' => 'sourdough.jpg',
                'label' => 'Premium',
                'label_color' => 'pink'
            ],
            [
                'id' => 6,
                'name' => 'Red Velvet Donut',
                'category' => 'Roti Manis',
                'price' => 13000,
                'rating' => 4.6,
                'reviews' => 74,
                'image' => 'red-velvet.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 7,
                'name' => 'Danish Pastry',
                'category' => 'Pastry',
                'price' => 14000,
                'rating' => 4.8,
                'reviews' => 91,
                'image' => 'danish.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 8,
                'name' => 'Roti Tawar Gandum',
                'category' => 'Roti Tawar',
                'price' => 18000,
                'rating' => 4.8,
                'reviews' => 156,
                'image' => 'roti-gandum.jpg',
                'label' => 'Terlaris',
                'label_color' => 'red'
            ],
            [
                'id' => 9,
                'name' => 'Kue Kering Nastar',
                'category' => 'Kue Kering',
                'price' => 45000,
                'rating' => 4.9,
                'reviews' => 203,
                'image' => 'nastar.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 10,
                'name' => 'Chocolate Eclair',
                'category' => 'Pastry',
                'price' => 17000,
                'rating' => 4.7,
                'reviews' => 88,
                'image' => 'eclair.jpg',
                'label' => 'Baru',
                'label_color' => 'red'
            ],
            [
                'id' => 11,
                'name' => 'Roti Abon Sapi',
                'category' => 'Roti Manis',
                'price' => 11000,
                'rating' => 4.6,
                'reviews' => 95,
                'image' => 'roti-abon.jpg',
                'label' => null,
                'label_color' => null
            ],
            [
                'id' => 12,
                'name' => 'Cinnamon Roll',
                'category' => 'Roti Manis',
                'price' => 15000,
                'rating' => 4.8,
                'reviews' => 142,
                'image' => 'cinnamon-roll.jpg',
                'label' => 'Terlaris',
                'label_color' => 'red'
            ],
        ];

        // Kategori dan jumlahnya
        $categories = [
            ['name' => 'Semua Produk', 'slug' => 'all', 'count' => 48],
            ['name' => 'Pastry', 'slug' => 'pastry', 'count' => 12],
            ['name' => 'Roti Manis', 'slug' => 'roti-manis', 'count' => 15],
            ['name' => 'Roti Tawar', 'slug' => 'roti-tawar', 'count' => 8],
            ['name' => 'Roti Prancis', 'slug' => 'roti-prancis', 'count' => 7],
            ['name' => 'Kue Kering', 'slug' => 'kue-kering', 'count' => 6],
        ];

        // Rating options
        $ratings = [
            ['stars' => 5, 'count' => 24],
            ['stars' => 4, 'count' => 18],
            ['stars' => 3, 'count' => 6],
        ];

        // Label options
        $labels = [
            ['name' => 'Terlaris', 'count' => 12],
            ['name' => 'Produk Baru', 'count' => 8],
            ['name' => 'Promo', 'count' => 5],
        ];

        // Filter berdasarkan kategori
        $filteredProducts = $products;
        if ($request->has('category') && $request->category != 'all') {
            $filteredProducts = array_filter($products, function($product) use ($request) {
                return strtolower(str_replace(' ', '-', $product['category'])) == $request->category;
            });
        }

        // Filter berdasarkan harga
        if ($request->has('min_price')) {
            $filteredProducts = array_filter($filteredProducts, function($product) use ($request) {
                return $product['price'] >= $request->min_price;
            });
        }
        if ($request->has('max_price')) {
            $filteredProducts = array_filter($filteredProducts, function($product) use ($request) {
                return $product['price'] <= $request->max_price;
            });
        }

        // Filter berdasarkan rating
        if ($request->has('rating')) {
            $filteredProducts = array_filter($filteredProducts, function($product) use ($request) {
                return floor($product['rating']) >= $request->rating;
            });
        }

        // Sorting
        $sortBy = $request->input('sort', 'popular');
        switch ($sortBy) {
            case 'price_low':
                usort($filteredProducts, function($a, $b) {
                    return $a['price'] - $b['price'];
                });
                break;
            case 'price_high':
                usort($filteredProducts, function($a, $b) {
                    return $b['price'] - $a['price'];
                });
                break;
            case 'newest':
                // Produk sudah diurutkan dari terbaru
                break;
            case 'popular':
            default:
                usort($filteredProducts, function($a, $b) {
                    return $b['reviews'] - $a['reviews'];
                });
                break;
        }

        // Pagination
        $perPage = 12;
        $currentPage = $request->input('page', 1);
        $totalProducts = count($filteredProducts);
        $totalPages = ceil($totalProducts / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedProducts = array_slice($filteredProducts, $offset, $perPage);

        return view('katalog.katalog', compact(
            'paginatedProducts',
            'categories',
            'ratings',
            'labels',
            'totalProducts',
            'currentPage',
            'totalPages',
            'perPage'
        ));
    }

    public function show($id)
    {
        // Detail produk (nanti bisa diganti dengan database)
        $products = [
            1 => [
                'id' => 1,
                'name' => 'Croissant Butter Premium',
                'category' => 'Pastry',
                'price' => 15000,
                'rating' => 4.9,
                'reviews' => 128,
                'image' => 'croissant.jpg',
                'description' => 'Croissant butter premium dengan lapisan yang renyah di luar dan lembut di dalam. Dibuat dengan mentega berkualitas tinggi dan teknik laminating tradisional Prancis.',
                'ingredients' => 'Tepung terigu, mentega premium, gula, garam, ragi, susu',
                'weight' => '80g',
                'stock' => 25,
                'label' => 'Terlaris'
            ],
            // Tambahkan produk lainnya sesuai kebutuhan
        ];

        $product = $products[$id] ?? null;

        if (!$product) {
            abort(404);
        }

        return view('katalog.detail', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Implementasi pencarian produk
        // Nanti bisa menggunakan database dengan query LIKE atau full-text search

        return redirect()->route('katalog.index')->with('search', $query);
    }

    // API untuk AJAX request (opsional)
    public function getProducts(Request $request)
    {
        $products = $this->index($request);
        return response()->json($products);
    }

    // Method untuk filter (opsional - jika menggunakan AJAX)
    public function filter(Request $request)
    {
        $filters = [
            'category' => $request->input('category'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'rating' => $request->input('rating'),
            'label' => $request->input('label'),
        ];

        // Apply filters dan return JSON
        return response()->json([
            'success' => true,
            'filters' => $filters
        ]);
    }
}
