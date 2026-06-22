<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Strawberry Cake & Bakery Tegal') | Strawberry Cake & Bakery</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sb-primary: #e94f70;
            --sb-primary-dark: #c93455;
            --sb-primary-soft: #fff1f4;
            --sb-accent: #f8b84e;
            --sb-dark: #0f172a;
            --sb-dark-2: #111827;
            --sb-text: #1f2937;
            --sb-muted: #64748b;
            --sb-border: #e5e7eb;
            --sb-surface: #ffffff;
            --sb-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        }

        html {
            scroll-padding-top: 92px;
        }

        body {
            font-family: 'Poppins', 'Inter', sans-serif;
            color: var(--sb-text);
            background:
                radial-gradient(circle at top left, rgba(233, 79, 112, 0.08), transparent 34rem),
                linear-gradient(180deg, #fff8fa 0%, #f8fafc 38%, #ffffff 100%);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        .app-navbar {
            min-height: 76px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.95);
            backdrop-filter: blur(14px);
        }

        .navbar-brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .brand-title {
            font-weight: 700;
            color: var(--sb-primary);
            line-height: 1.1;
            letter-spacing: 0;
            white-space: normal;
        }

        .brand-subtitle {
            display: block;
            color: var(--sb-muted);
            font-size: 0.72rem;
            font-weight: 500;
            margin-top: 0.12rem;
        }

        .nav-link {
            border-radius: 999px;
            font-weight: 500;
            color: #334155;
            padding: 0.55rem 0.9rem !important;
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--sb-primary);
            background-color: var(--sb-primary-soft);
        }

        .navbar-toggler {
            border-color: var(--sb-border);
            box-shadow: none !important;
        }

        .auth-chip {
            background-color: #f8fafc;
            border: 1px solid var(--sb-border);
            color: #475569;
            font-weight: 600;
        }

        .btn,
        button,
        input,
        select,
        textarea {
            letter-spacing: 0;
        }

        .btn {
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-primary,
        .btn-strawberry {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 42px;
            padding: 0.62rem 1.15rem;
            border-radius: 999px;
            background-color: var(--sb-primary);
            border-color: var(--sb-primary);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 22px rgba(233, 79, 112, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-strawberry:hover {
            background-color: var(--sb-primary-dark);
            border-color: var(--sb-primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(201, 52, 85, 0.28);
        }

        .btn-outline-secondary {
            border-color: var(--sb-border);
            color: #475569;
        }

        .btn-outline-secondary:hover {
            background-color: var(--sb-dark);
            border-color: var(--sb-dark);
            color: #fff;
        }

        .cart-button {
            width: 44px;
            height: 44px;
            color: var(--sb-primary);
        }

        .dropdown-menu {
            border: 1px solid var(--sb-border);
            border-radius: 0.9rem;
            padding: 0.45rem;
        }

        .dropdown-item {
            border-radius: 0.65rem;
            font-weight: 500;
            padding: 0.65rem 0.8rem;
        }

        main.app-main {
            clear: both;
            overflow-x: hidden;
        }

        .hero-section {
            background:
                linear-gradient(135deg, rgba(233, 79, 112, 0.96), rgba(248, 184, 78, 0.88)),
                url("{{ asset('images/logo.png') }}");
            background-position: center;
            background-size: cover;
            border-bottom: 1px solid rgba(233, 79, 112, 0.18);
        }

        .card,
        .bg-white.rounded-2xl,
        .bg-white.rounded-xl,
        .bg-white.rounded-lg {
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: var(--sb-shadow);
        }

        .card {
            border-radius: 1rem;
            overflow: hidden;
        }

        .card-img-top {
            background-color: #fff1f4;
        }

        input,
        select,
        textarea,
        .form-control,
        .form-select {
            border-color: #dbe3ed !important;
            border-radius: 0.85rem !important;
        }

        input:focus,
        select:focus,
        textarea:focus,
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(233, 79, 112, 0.72) !important;
            box-shadow: 0 0 0 0.2rem rgba(233, 79, 112, 0.14) !important;
        }

        .badge {
            letter-spacing: 0;
        }

        .site-footer {
            background:
                linear-gradient(180deg, #111827 0%, #0f172a 100%);
            clear: both;
            border-top: 4px solid rgba(233, 79, 112, 0.72);
        }

        .footer-brand-mark {
            width: 48px;
            height: 48px;
            object-fit: contain;
            background-color: #fff;
            border-radius: 14px;
            padding: 0.35rem;
        }

        .footer-title {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.72);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .footer-link:hover {
            color: #fff;
            transform: translateX(3px);
        }

        .footer-meta {
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.75;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem;
                border: 1px solid var(--sb-border);
                border-radius: 1rem;
                background-color: #fff;
                box-shadow: var(--sb-shadow);
            }

            .navbar-nav {
                gap: 0.2rem;
            }

            .brand-title {
                font-size: 0.98rem;
            }

            .brand-subtitle {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            main.app-main {
                margin-top: 72px !important;
            }

            .navbar-brand img {
                width: 42px;
                height: 42px;
            }

            .brand-title {
                max-width: 190px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white fixed-top app-navbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Strawberry Cake & Bakery Logo">
                    <span>
                        <span class="brand-title">Strawberry Cake & Bakery</span>
                        <span class="brand-subtitle">Tegal Premium Bakery</span>
                    </span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Hubungi Kami</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        @auth
                            <a href="{{ route('cart.index') }}" class="btn btn-light border rounded-circle d-inline-flex align-items-center justify-content-center position-relative cart-button" aria-label="Keranjang Belanja">
                                <i class="fa-solid fa-cart-shopping"></i>
                                @php
                                    $cartCount = \App\Models\Cart::countItemsForUser(auth()->id());
                                @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>

                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-user"></i>
                                    <span>{{ auth()->user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fa-regular fa-id-card me-2"></i>Profil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="fa-solid fa-receipt me-2"></i>Pesanan Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <span class="badge rounded-pill auth-chip">Guest</span>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-strawberry btn-sm">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="app-main py-5" style="min-height: 80vh; margin-top: 76px;">
        @yield('content')
    </main>

    <footer class="site-footer text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Cake & Bakery" class="footer-brand-mark">
                        <h2 class="h5 fw-bold mb-0">Strawberry Cake & Bakery</h2>
                    </div>
                    <p class="mb-3 footer-meta">
                        Strawberry Cake & Bakery Tegal - Premium pre-order bakery system.
                    </p>
                    <div class="d-flex gap-2">
                        <span class="badge rounded-pill text-bg-light">Pre-order</span>
                        <span class="badge rounded-pill text-bg-light">Fresh Bakery</span>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h2 class="h6 footer-title">Quick Links</h2>
                    <ul class="list-unstyled d-grid gap-2 mb-0">
                        <li><a class="footer-link" href="{{ route('home') }}"><i class="fa-solid fa-chevron-right small"></i>Home</a></li>
                        <li><a class="footer-link" href="{{ route('products.index') }}"><i class="fa-solid fa-chevron-right small"></i>Katalog</a></li>
                        <li><a class="footer-link" href="{{ route('about') }}"><i class="fa-solid fa-chevron-right small"></i>Tentang Kami</a></li>
                        <li><a class="footer-link" href="{{ route('contact') }}"><i class="fa-solid fa-chevron-right small"></i>Hubungi Kami</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h2 class="h6 footer-title">Operational Info & Support</h2>
                    <ul class="list-unstyled d-grid gap-2 mb-0">
                        <li><a class="footer-link" href="{{ route('faq') }}"><i class="fa-regular fa-circle-question"></i>FAQ</a></li>
                        <li><a class="footer-link" href="{{ route('terms') }}"><i class="fa-solid fa-shield-halved"></i>Kebijakan Privasi</a></li>
                        <li><a class="footer-link" href="{{ route('order.track') }}"><i class="fa-solid fa-truck-fast"></i>Lacak Pesanan</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h2 class="h6 footer-title">Store Metadata</h2>
                    <ul class="list-unstyled d-grid gap-2 mb-0 footer-meta">
                        <li><i class="fa-solid fa-location-dot me-2 text-white"></i>Jl. AR. Hakim No.149, Mangkukusuman, Kota Tegal</li>
                        <li><i class="fa-brands fa-whatsapp me-2 text-white"></i>Whatsapp: +62 815-7173-138</li>
                        <li><i class="fa-regular fa-clock me-2 text-white"></i>Setiap hari, 08.00 - 21.00 WIB</li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary opacity-50 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-white-50 small">
                <p class="mb-0">&copy; {{ now()->year }} Strawberry Cake & Bakery Tegal. All rights reserved.</p>
                <p class="mb-0">Sistem pre-order bakery untuk pelanggan Tegal.</p>
            </div>
        </div>
    </footer>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
