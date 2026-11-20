<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Strawberry Cake & Bakery Tegal') | Strawberry Cake & Bakery</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sb-primary: #e94f70;
            --sb-primary-dark: #c93455;
            --sb-primary-soft: #fff1f4;
            --sb-dark: #1e293b;
            --sb-text: #334155;
            --sb-muted: #64748b;
            
            /* Override Bootstrap Primary Color */
            --bs-primary: #e94f70;
            --bs-primary-rgb: 233, 79, 112;
        }
        
        /* Comprehensive Bootstrap Primary Override */
        .text-primary { color: var(--sb-primary) !important; }
        .bg-primary { background-color: var(--sb-primary) !important; }
        .border-primary { border-color: var(--sb-primary) !important; }
        
        .btn-primary {
            background-color: var(--sb-primary) !important;
            border-color: var(--sb-primary) !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--sb-primary-dark) !important;
            border-color: var(--sb-primary-dark) !important;
        }
        
        .btn-outline-primary {
            color: var(--sb-primary) !important;
            border-color: var(--sb-primary) !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--sb-primary) !important;
            border-color: var(--sb-primary) !important;
            color: #ffffff !important;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--sb-text);
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            scroll-padding-top: 100px;
        }

        h1, h2, h3, h4, h5, h6, .brand-title {
            font-family: 'Playfair Display', serif;
        }

        .hover-primary:hover {
            color: var(--sb-primary) !important;
            transition: color 0.2s ease;
        }

        /* --- Header & Navbar --- */
        .top-bar {
            background-color: #0f172a;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .app-navbar {
            background-color: #ffffff;
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 50px;
            object-fit: contain;
        }

        .brand-title {
            color: #0f172a;
            font-size: 1.4rem;
            line-height: 1;
            margin-bottom: 2px;
        }

        .brand-subtitle {
            color: var(--sb-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-link {
            color: #475569;
            font-weight: 500;
            padding: 1rem 1.25rem !important;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--sb-primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--sb-primary);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 40%;
        }

        /* --- Buttons --- */
        .btn {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary, .btn-strawberry {
            background-color: var(--sb-primary);
            border-color: var(--sb-primary);
            color: white;
            box-shadow: 0 4px 14px rgba(233, 79, 112, 0.25);
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-strawberry:hover {
            background-color: var(--sb-primary-dark);
            border-color: var(--sb-primary-dark);
            transform: translateY(-1.5px);
            box-shadow: 0 6px 20px rgba(233, 79, 112, 0.4);
        }

        .btn-outline-secondary {
            border: 1px solid #cbd5e1;
            color: #475569;
            background-color: white;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .cart-icon-btn {
            position: relative;
            color: #475569;
            font-size: 1.25rem;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .cart-icon-btn:hover {
            color: var(--sb-primary);
        }

        .cart-badge {
            position: absolute;
            top: 0px;
            right: -5px;
            font-size: 0.65rem;
            padding: 0.35em 0.5em;
        }

        /* --- Main Content --- */
        main.app-main {
            flex-grow: 1;
        }

        /* --- Footer --- */
        .site-footer {
            background-color: #0f172a;
            color: #cbd5e1;
            padding-top: 4rem;
            padding-bottom: 2rem;
            margin-top: auto;
        }

        .footer-title {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-link:hover {
            color: var(--sb-primary);
            transform: translateX(5px);
        }

        .footer-icon-circle {
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .footer-icon-circle:hover {
            background-color: var(--sb-primary);
            color: white;
            transform: translateY(-3px);
        }

        /* --- Force Navbar & Menu Items Visibility --- */
        .app-navbar {
            background-color: #ffffff !important;
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .app-navbar .container {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .app-navbar .nav-link {
            color: #475569 !important;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            position: relative;
            transition: color 0.3s ease;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .app-navbar .nav-link:hover, 
        .app-navbar .nav-link.active {
            color: var(--sb-primary) !important;
            font-weight: 600;
        }

        @media (min-width: 992px) {
            .app-navbar .navbar-collapse {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                flex-basis: auto !important;
                z-index: 1035;
            }

            .app-navbar .navbar-nav {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 0.5rem !important;
            }
        }

        @media (max-width: 991.98px) {
            .app-navbar .navbar-collapse:not(.show) {
                display: none !important;
            }

            .app-navbar .navbar-collapse.show {
                display: block !important;
                background: #ffffff !important;
                padding: 1.25rem !important;
                border-radius: 12px !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
                margin-top: 10px !important;
                border: 1px solid #f1f5f9 !important;
            }

            .app-navbar .navbar-nav {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
            }

            .nav-link::after { display: none; }
        }

        /* --- Accordion & FAQ Styling Fixes --- */
        .accordion-item {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            margin-bottom: 0.75rem !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03) !important;
        }

        .accordion-button {
            color: #1e293b !important;
            background-color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 1.05rem !important;
            padding: 1.1rem 1.35rem !important;
            transition: all 0.25s ease !important;
        }

        .accordion-button:hover {
            color: var(--sb-primary) !important;
            background-color: #fff8f9 !important;
        }

        .accordion-button:not(.collapsed) {
            color: var(--sb-primary-dark, #c93455) !important;
            background-color: var(--sb-primary-soft, #fff1f4) !important;
            box-shadow: inset 0 -1px 0 rgba(233, 79, 112, 0.15) !important;
            font-weight: 700 !important;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.2rem rgba(233, 79, 112, 0.2) !important;
            border-color: rgba(233, 79, 112, 0.3) !important;
        }

        .accordion-body {
            background-color: #ffffff !important;
            color: #334155 !important;
            font-size: 0.95rem !important;
            line-height: 1.7 !important;
            padding: 1.25rem 1.35rem !important;
            border-top: 1px solid #f1f5f9 !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }

        .accordion-body p,
        .accordion-body span,
        .accordion-body div,
        .accordion-body * {
            color: #334155 !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Custom FAQ Toggle (faq.blade.php & kontak.blade.php) */
        .faq-question {
            color: #1e293b !important;
            background-color: #ffffff !important;
            transition: all 0.25s ease !important;
        }

        .faq-question:hover {
            color: var(--sb-primary) !important;
            background-color: #fff8f9 !important;
        }

        .faq-question.expanded {
            color: var(--sb-primary-dark, #c93455) !important;
            background-color: var(--sb-primary-soft, #fff1f4) !important;
            font-weight: 700 !important;
        }

        .faq-answer {
            background-color: #ffffff !important;
            color: #334155 !important;
        }

        .contact-faq details {
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
        }

        .contact-faq details[open] {
            border-color: rgba(233, 79, 112, 0.3);
            background: var(--sb-primary-soft, #fff1f4);
            box-shadow: 0 4px 15px rgba(233, 79, 112, 0.08);
        }

        .contact-faq summary {
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            list-style: none;
        }

        .contact-faq summary::-webkit-details-marker {
            display: none;
        }

        .contact-faq details[open] summary {
            color: var(--sb-primary-dark, #c93455);
            font-weight: 700;
        }

        .contact-faq p {
            color: #334155;
        }
    </style>

    @stack('styles')
</head>
<body>

        <!-- Main Navbar -->
        <nav class="navbar navbar-expand-lg app-navbar py-2 py-lg-3 shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery Logo" style="mix-blend-mode: multiply; background-color: transparent;">
                    <div class="d-flex flex-column">
                        <span class="brand-title">Strawberry Bakery</span>
                        <span class="brand-subtitle">Premium Cake & Pastry</span>
                    </div>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Katalog Produk</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                        </li>

                    </ul>

                    <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                        @auth
                            <!-- Cart Icon -->
                            <a href="{{ route('cart.index') }}" class="cart-icon-btn text-decoration-none" aria-label="Keranjang">
                                <i class="fa-solid fa-bag-shopping"></i>
                                @php
                                    $cartCount = \App\Models\Cart::countItemsForUser(auth()->id());
                                @endphp
                                @if($cartCount > 0)
                                    <span class="badge rounded-pill bg-danger cart-badge shadow-sm">{{ $cartCount }}</span>
                                @endif
                            </a>

                            <!-- User Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-pill px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;" referrerpolicy="no-referrer">
                                    @else
                                        <i class="fa-regular fa-circle-user fs-5"></i>
                                    @endif
                                    <span class="fw-medium">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px;">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                            <i class="fa-regular fa-id-card me-2 text-primary"></i> Profil Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                                            <i class="fa-solid fa-receipt me-2 text-primary"></i> Riwayat Pesanan
                                        </a>
                                    </li>
                                    @if(in_array(auth()->user()->role, ['admin', 'owner', 'superadmin']))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-chart-line me-2 text-primary"></i> Dashboard Admin
                                        </a>
                                    </li>
                                    @elseif(auth()->user()->role === 'kasir')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('kasir.pos') }}">
                                            <i class="fa-solid fa-cash-register me-2 text-primary"></i> Mesin POS Kasir
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="form-confirm" data-confirm-message="Apakah Anda yakin ingin keluar dari akun?">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger fw-medium">
                                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('login') }}" class="btn text-secondary fw-semibold px-3 hover-primary border-0 bg-transparent">Masuk</a>
                                <a href="{{ route('register') }}" class="btn btn-primary px-4 shadow-sm">Daftar</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="app-main">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row g-5 mb-5">
                <!-- Brand & Description -->
                <div class="col-12 col-lg-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery Logo" style="height: 50px; background-color: transparent;">
                        <h2 class="h4 text-white mb-0" style="font-family: 'Playfair Display', serif;">Strawberry<br>Bakery</h2>
                    </div>
                    <p class="mb-4 text-white-50" style="line-height: 1.8;">
                        Menghadirkan kreasi roti dan kue premium berkualitas tinggi untuk setiap momen spesial Anda. Dibuat dengan bahan pilihan dan dedikasi penuh.
                    </p>
                    <div class="mb-4">
                        <span class="badge rounded-pill px-3 py-1.5 fw-medium tracking-wide shadow-sm" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fbcfe8; font-family: 'Poppins', sans-serif;">
                            ✨ Fresh Every Morning
                        </span>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="https://instagram.com/strawberrycaketegal" target="_blank" class="footer-icon-circle text-decoration-none">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/628157173118" target="_blank" class="footer-icon-circle text-decoration-none">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="col-12 col-md-4 col-lg-2 offset-lg-1">
                    <h3 class="footer-title">Perusahaan</h3>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ route('about') }}" class="footer-link">Tentang Kami</a></li>
                        <li><a href="{{ route('products.index') }}" class="footer-link">Katalog Produk</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Lokasi Toko</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Support Links -->
                <div class="col-12 col-md-4 col-lg-2">
                    <h3 class="footer-title">Bantuan</h3>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ route('faq') ?? '#' }}" class="footer-link">FAQ / Bantuan</a></li>
                        <li><a href="{{ route('order.track') ?? '#' }}" class="footer-link">Lacak Pesanan</a></li>
                        <li><a href="{{ route('terms') ?? '#' }}" class="footer-link">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('terms') ?? '#' }}" class="footer-link">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <!-- Contact Info & Payments -->
                <div class="col-12 col-md-4 col-lg-3">
                    <h3 class="footer-title">Hubungi Kami</h3>
                    <ul class="list-unstyled d-flex flex-column gap-3 text-white-50">
                        <li class="d-flex align-items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-primary"></i>
                            <span>Jl. AR. Hakim No.149,<br>Mangkukusuman, Kota Tegal</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-phone text-primary"></i>
                            <span>+62 815-7173-138</span>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <i class="fa-regular fa-clock mt-1 text-primary"></i>
                            <span>Jam Operasional:<br>Senin–Sabtu<br>08.00–20.00 WIB</span>
                        </li>
                    </ul>

                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <h4 class="h6 text-white mb-3 font-semibold">Metode Pembayaran</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-white text-dark shadow-sm">QRIS</span>
                            <span class="badge bg-white text-dark shadow-sm">BCA</span>
                            <span class="badge bg-white text-dark shadow-sm">BRI</span>
                            <span class="badge bg-white text-dark shadow-sm">Mandiri</span>
                            <span class="badge bg-white text-dark shadow-sm">Dana</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-top border-secondary pt-4 mt-4 text-center text-md-start d-md-flex justify-content-between align-items-center text-white-50" style="border-opacity: 0.2 !important; font-size: 0.85rem;">
                <p class="mb-2 mb-md-0">&copy; {{ now()->year }} Strawberry Cake & Bakery. All rights reserved.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <span>Designed with ❤️ in Tegal</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <div class="wa-float-container" id="waFloatContainer" style="display: none;">
        <div class="wa-bubble shadow-lg d-none d-md-block">
            <button class="wa-close" aria-label="Tutup" onclick="document.getElementById('waFloatContainer').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
            <div class="wa-bubble-text">
                <span class="d-block fw-bold text-dark mb-1" style="font-size: 14px;">💬 Ada yang ingin ditanyakan?</span>
                <span class="d-block text-muted" style="font-size: 12px;">Kami siap membantu 😊</span>
            </div>
        </div>
        <a href="https://wa.me/628157173118" class="whatsapp-float" target="_blank" aria-label="Chat WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    </div>

    <style>
        .wa-float-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            align-items: flex-end;
            gap: 15px;
            animation: fade-up-wa 0.5s ease-out forwards;
        }

        .wa-bubble {
            background: white;
            border-radius: 12px;
            padding: 12px 35px 12px 15px;
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 5px;
        }

        .wa-bubble::after {
            content: '';
            position: absolute;
            right: -8px;
            bottom: 15px;
            border-left: 8px solid white;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        .wa-close {
            position: absolute;
            top: 5px;
            right: 8px;
            background: none;
            border: none;
            color: #999;
            font-size: 14px;
            padding: 0;
            cursor: pointer;
            transition: color 0.2s;
        }
        .wa-close:hover { color: #333; }

        .whatsapp-float {
            background-color: #25d366;
            color: #FFF;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .whatsapp-float i {
            font-size: 32px;
        }

        .whatsapp-float:hover {
            background-color: #128C7E;
            color: #FFF;
            transform: scale(1.1);
        }

        @keyframes fade-up-wa {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .wa-float-container {
                bottom: 20px;
                right: 20px;
            }
            .whatsapp-float {
                width: 50px;
                height: 50px;
            }
            .whatsapp-float i {
                font-size: 26px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto show WhatsApp bubble after 3 seconds
            setTimeout(function() {
                const waContainer = document.getElementById('waFloatContainer');
                if(waContainer) {
                    waContainer.style.display = 'flex';
                }
            }, 3000);
        });
    </script>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('form-confirm')) {
                e.preventDefault();
                const msg = form.getAttribute('data-confirm-message') || 'Apakah Anda yakin?';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.classList.remove('form-confirm');
                        form.submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
