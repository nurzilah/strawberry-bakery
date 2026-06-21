<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Strawberry Bakery') }} - @yield('title', 'Strawberry Bakery')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

    <style>
        :root {
            --primary: #EF7A85;
            --primary-dark: #E55A67;
            --secondary: #FFE5E8;
            --text-dark: #2D3748;
            --text-gray: #718096;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 122, 133, 0.3);
        }

        .hero-section {
            background: linear-gradient(135deg, #EF7A85 0%, #F8A5AE 100%);
        }

        .card {
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-new {
            background-color: #EF7A85;
            color: white;
        }

        .badge-promo {
            background-color: #F56565;
            color: white;
        }

        .badge-featured {
            background-color: #ED8936;
            color: white;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery" class="h-16 w-auto object-contain">
                </a>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-500 font-medium transition">Home</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-pink-500 font-medium transition">Katalog</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-pink-500 font-medium transition">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-pink-500 font-medium transition">Hubungi Kami</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                <!-- Search -->
                <div x-data="{ open: false }" class="relative">
                    <!-- Icon -->
                    <button
                        type="button"
                        @click="open = !open"
                        class="p-2 hover:bg-gray-100 rounded-full transition relative z-50 cursor-pointer"
                        aria-label="Search">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Popup Search -->
                    <div
                        x-show="open"
                        x-transition
                        @click.outside="open = false"
                        class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-xl p-4 z-40">

                        <input
                            type="text"
                            placeholder="Cari produk, halaman..."
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-pink-300">

                        <p class="text-xs text-gray-400 mt-2">
                            Ketik untuk mencari
                        </p>
                    </div>
                </div>


                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @auth
                            @if(optional(auth()->user()->carts)->count() > 0)

                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ auth()->user()->carts->sum('quantity') }}
                                </span>
                            @endif
                        @endauth
                    </a>

                <!-- User Profile (Direct Link) -->
                @auth
                    <a href="{{ route('profile.show') }}"
                    class="p-2 hover:bg-gray-100 rounded-full transition"
                    title="Profil Saya">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery" class="h-16 w-auto object-contain mb-4">
                    <p class="text-gray-400 text-sm">Nikmati roti segar berkualitas premium dengan sistem pre-order yang mudah dan praktis</p>
                </div>

                <!-- Menu -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Menu</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Katalog</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Bantuan -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Bantuan</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('order.track') }}" class="hover:text-white transition">Lacak Pesanan</a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>📍 Jl. AR. Hakim No.149, Mangkukusuman</li>
                        <li>📞 +62 815-7173-138</li>
                        <li>✉️ info@strawberrybakery.com</li>
                        <li>🕒 Senin - Minggu: 08:00 - 21:00</li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-800 my-8">

            <div class="text-center text-gray-400 text-sm">
                <p>&copy; 2025 Strawberry Bakery. All rights reserved. Dibuat dengan ❤️ di Indonesia</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
