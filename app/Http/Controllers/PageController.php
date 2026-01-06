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
        return view('tentang.tentang');
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
