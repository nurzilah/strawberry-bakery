<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function products()
    {
        return view('katalog.katalog');
    }

    public function about()
    {
        $totalReviews = \App\Models\ProductReview::where('is_approved', true)->count();
        $averageRating = \App\Models\ProductReview::where('is_approved', true)->avg('rating') ?? 5.0;
        $totalProducts = \App\Models\Product::where('is_available', true)->count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();

        return view('tentang.tentang', compact('totalReviews', 'averageRating', 'totalProducts', 'totalCustomers'));
    }

    public function contact()
    {
        return view('kontak.kontak');
    }

        public function faq()
    {
        return view('faq.faq');
    }

    public function terms()
    {
        return view('terms.terms');
    }

    public function trackOrder()
    {
        return view('order.order');
    }

}
