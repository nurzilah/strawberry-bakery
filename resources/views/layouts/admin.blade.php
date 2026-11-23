<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Strawberry Bakery') }} - @yield('title', 'Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --pink-pastel: #ffb6c1;
            --pink-dark: #e094a0;
            --cream: #fffdd0;
            --soft-brown: #d2b48c;
            --sidebar-width: 250px;
            
            /* Override Bootstrap Primary Color */
            --bs-primary: #e94f70;
            --bs-primary-rgb: 233, 79, 112;
            --sb-primary: #e94f70;
            --sb-primary-dark: #c93455;
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

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        body.dark-mode {
            background-color: #212529;
            color: #f8f9fa;
        }

        .dark-mode .card {
            background-color: #343a40;
            border-color: #495057;
            color: #f8f9fa;
        }

        .dark-mode .table {
            color: #f8f9fa;
        }

        .dark-mode .text-muted {
            color: #adb5bd !important;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #ffffff;
            border-right: 1px solid rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 1040;
        }

        #sidebar.compact {
            min-width: 80px;
            max-width: 80px;
        }

        .dark-mode #sidebar {
            background: #2c3238;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar .sidebar-header {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-height: 72px;
            transition: all 0.3s ease;
            overflow: hidden;
            white-space: normal;
        }

        .main-content-body {
            padding-top: 1.75rem !important;
            padding-bottom: 3rem !important;
        }

        #sidebar.compact .sidebar-header {
            padding: 1.25rem 0;
            justify-content: center;
        }

        #sidebar.compact .sidebar-header .header-text {
            display: none !important;
        }

        .dark-mode #sidebar .sidebar-header {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar ul.components {
            padding: 1.25rem 0.85rem;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        #sidebar ul li a {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        #sidebar.compact ul li a {
            padding: 0.75rem;
            justify-content: center;
        }

        #sidebar.compact ul li a span {
            display: none;
        }

        .dark-mode #sidebar ul li a {
            color: #cbd5e1;
        }

        #sidebar ul li a i {
            width: 24px;
            text-align: center;
            font-size: 1.15rem;
            margin-right: 0.75rem;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        #sidebar.compact ul li a i {
            margin-right: 0;
            font-size: 1.25rem;
        }

        .dark-mode #sidebar ul li a i {
            color: #64748b;
        }

        #sidebar ul li a:hover {
            background: rgba(233, 79, 112, 0.05);
            color: var(--sb-primary);
        }

        .dark-mode #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffb6c1;
        }

        #sidebar ul li a:hover i {
            color: var(--sb-primary);
        }

        .dark-mode #sidebar ul li a:hover i {
            color: #ffb6c1;
        }

        #sidebar ul li.active > a {
            background: rgba(233, 79, 112, 0.1);
            color: var(--sb-primary-dark);
            font-weight: 600;
            position: relative;
        }
        
        #sidebar ul li.active > a::before {
            content: "";
            position: absolute;
            left: -0.85rem;
            top: 10%;
            height: 80%;
            width: 4px;
            background: var(--sb-primary);
            border-radius: 0 4px 4px 0;
        }

        #sidebar.compact ul li.active > a::before {
            left: -0.5rem;
        }

        .dark-mode #sidebar ul li.active > a {
            background: rgba(233, 79, 112, 0.2);
            color: #ffb6c1;
        }

        .dark-mode #sidebar ul li.active > a::before {
            background: #ffb6c1;
        }

        #sidebar ul li.active > a i {
            color: var(--sb-primary);
        }

        .dark-mode #sidebar ul li.active > a i {
            color: #ffb6c1;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1030;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        #content {
            width: 100%;
            min-width: 0;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }

        .dark-mode #content {
            background: #212529;
        }

        .navbar-custom {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.75rem 1.5rem;
            z-index: 1010;
        }

        .dark-mode .navbar-custom {
            background: #2c3238;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                transform: translateX(-100%);
                box-shadow: 4px 0 15px rgba(0,0,0,0.05);
            }
            #sidebar.compact {
                min-width: 260px;
                max-width: 260px;
            }
            #sidebar.compact .sidebar-header {
                padding: 1.25rem 1.5rem;
                justify-content: flex-start;
            }
            #sidebar.compact .sidebar-header .header-text {
                display: flex !important;
            }
            #sidebar.compact ul li a {
                padding: 0.75rem 1rem;
                justify-content: flex-start;
            }
            #sidebar.compact ul li a span {
                display: inline;
            }
            #sidebar.compact ul li a i {
                margin-right: 0.75rem;
                font-size: 1.15rem;
            }
            #sidebar.active {
                transform: translateX(0);
            }
        }

        .w-5 {
            width: 1.25rem !important;
        }

        .h-5 {
            height: 1.25rem !important;
        }

        nav[role="navigation"] svg {
            max-width: 16px;
            display: inline-block;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="SB" style="width: 40px; height: 40px; object-fit: contain; flex-shrink: 0;">
                <div class="d-flex flex-column text-start header-text" style="min-width: 0; flex: 1; white-space: normal; overflow: hidden;">
                    <span class="fw-bold text-dark dark-text-light" style="font-size: 0.85rem; line-height: 1.25; white-space: normal; word-break: break-word;" title="{{ auth()->user()->name }}">
                        {{ auth()->user()->name }}
                    </span>
                    <div class="mt-1">
                        @php
                            $role = strtolower(auth()->user()->role ?? 'guest');
                            if(in_array($role, ['owner', 'superadmin'])) {
                                $bg = '#d63384';
                                $label = 'OWNER';
                            } elseif($role === 'admin') {
                                $bg = '#0d6efd';
                                $label = 'IT DEVELOPER';
                            } elseif($role === 'kasir') {
                                $bg = '#198754';
                                $label = 'KASIR';
                            } elseif($role === 'kurir') {
                                $bg = '#0dcaf0';
                                $label = 'KURIR';
                            } else {
                                $bg = '#6c757d';
                                $label = strtoupper($role);
                            }
                        @endphp
                        <span class="badge" style="background-color: {{ $bg }}; color: white; font-size: 0.65rem; padding: 0.35em 0.6em; letter-spacing: 0.5px;">
                            {{ $label }}
                        </span>
                    </div>
                </div>
            </div>

            <ul class="list-unstyled components">

                {{-- ===== OWNER: akses semua ===== --}}
                @if(in_array(auth()->user()->role, ['owner', 'superadmin']))

                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" title="Dashboard">
                            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.recommendation.visualization') ? 'active' : '' }}">
                        <a href="{{ route('admin.recommendation.visualization') }}" title="Visualisasi Rekomendasi">
                            <i class="fas fa-project-diagram"></i> <span>Visualisasi Rekomendasi</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}" title="Kelola Produk">
                            <i class="fas fa-box-open"></i> <span>Kelola Produk</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}" title="Kelola Kategori">
                            <i class="fas fa-tags"></i> <span>Kelola Kategori</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.promos.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.promos.index') }}" title="Kelola Promo & Diskon">
                            <i class="fas fa-percent"></i> <span>Kelola Promo & Diskon</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.vouchers.index') }}" title="Kelola Voucher">
                            <i class="fas fa-ticket-alt"></i> <span>Kelola Voucher</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.stock.index') }}" title="Riwayat Penambahan Stok">
                            <i class="fas fa-boxes"></i> <span>Riwayat Penambahan Stok</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" title="Pesanan Online">
                            <i class="fas fa-shopping-cart"></i> <span>Pesanan Online</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                        <a href="{{ route('kasir.pos') }}" title="Mesin POS / Kasir">
                            <i class="fas fa-cash-register"></i> <span>Mesin POS / Kasir</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('kasir.transactions.index') }}" title="Riwayat Transaksi POS">
                            <i class="fas fa-history"></i> <span>Riwayat Transaksi POS</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.kurir.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.kurir.index') }}" title="Kelola Kurir">
                            <i class="fas fa-motorcycle"></i> <span>Kelola Kurir</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <a href="{{ route('owner.users.index') }}" title="Manajemen Akun">
                            <i class="fas fa-users"></i> <span>Manajemen Akun</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                        <a href="{{ route('owner.reports.index') }}" title="Laporan Penjualan">
                            <i class="fas fa-file-invoice-dollar"></i> <span>Laporan Penjualan</span>
                        </a>
                    </li>

                {{-- ===== ADMIN / IT DEVELOPER ===== --}}
                @elseif(auth()->user()->role === 'admin')

                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" title="Dashboard">
                            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.recommendation.visualization') ? 'active' : '' }}">
                        <a href="{{ route('admin.recommendation.visualization') }}" title="Visualisasi Rekomendasi">
                            <i class="fas fa-project-diagram"></i> <span>Visualisasi Rekomendasi</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}" title="Kelola Produk">
                            <i class="fas fa-box-open"></i> <span>Kelola Produk</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}" title="Kelola Kategori">
                            <i class="fas fa-tags"></i> <span>Kelola Kategori</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.promos.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.promos.index') }}" title="Kelola Promo & Diskon">
                            <i class="fas fa-percent"></i> <span>Kelola Promo & Diskon</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.vouchers.index') }}" title="Kelola Voucher">
                            <i class="fas fa-ticket-alt"></i> <span>Kelola Voucher</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.stock.index') }}" title="Riwayat Penambahan Stok">
                            <i class="fas fa-boxes"></i> <span>Riwayat Penambahan Stok</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" title="Pesanan Online">
                            <i class="fas fa-shopping-cart"></i> <span>Pesanan Online</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                        <a href="{{ route('kasir.pos') }}" title="Mesin POS / Kasir">
                            <i class="fas fa-cash-register"></i> <span>Mesin POS / Kasir</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('kasir.transactions.index') }}" title="Riwayat Transaksi POS">
                            <i class="fas fa-history"></i> <span>Riwayat Transaksi POS</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.kurir.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.kurir.index') }}" title="Kelola Kurir">
                            <i class="fas fa-motorcycle"></i> <span>Kelola Kurir</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <a href="{{ route('owner.users.index') }}" title="Manajemen Akun">
                            <i class="fas fa-users"></i> <span>Manajemen Akun</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                        <a href="{{ route('owner.reports.index') }}" title="Laporan Penjualan">
                            <i class="fas fa-file-invoice-dollar"></i> <span>Laporan Penjualan</span>
                        </a>
                    </li>

                {{-- ===== KASIR ===== --}}
                @elseif(auth()->user()->role === 'kasir')

                    <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                        <a href="{{ route('kasir.pos') }}" title="Mesin POS / Kasir">
                            <i class="fas fa-cash-register"></i> <span>Mesin POS / Kasir</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" title="Update Pesanan">
                            <i class="fas fa-shopping-cart"></i> <span>Update Pesanan</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('kasir.transactions.index') }}" title="Riwayat Transaksi">
                            <i class="fas fa-history"></i> <span>Riwayat Transaksi</span>
                        </a>
                    </li>

                {{-- ===== KURIR ===== --}}
                @elseif(auth()->user()->role === 'kurir')

                    <li class="{{ request()->routeIs('kurir.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('kurir.dashboard') }}" title="Dashboard Pengantaran">
                            <i class="fas fa-motorcycle"></i> <span>Dashboard Pengantaran</span>
                        </a>
                    </li>

                @endif

            </ul>

        </nav>

        <!-- Overlay for mobile sidebar -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto gap-3">
                        <button class="btn btn-outline-secondary rounded-circle" id="themeToggle">
                            <i class="fas fa-moon"></i>
                        </button>

                        <!-- Notification Dropdown -->
                        @if(in_array(auth()->user()->role, ['admin', 'owner', 'superadmin']))
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary rounded-circle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Pesanan">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notificationBadge">
                                    0
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0" aria-labelledby="notificationDropdown" style="width: 350px; overflow: hidden;">
                                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark small mb-0">
                                        <i class="fas fa-bell me-1 text-primary"></i> Notifikasi
                                    </span>
                                    <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-primary small fw-semibold" onclick="markAllAsRead(event)">
                                        <i class="fas fa-check-double me-1"></i>Tandai semua dibaca
                                    </button>
                                </div>
                                <div id="notificationList" style="max-height: 380px; overflow-y: auto;">
                                    <div class="text-center text-muted py-4 small">Memuat notifikasi...</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Lihat Web</a>

                        <form action="{{ route('logout') }}" method="POST" class="m-0 form-confirm" data-confirm-message="Apakah Anda yakin ingin keluar dari halaman sistem?">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-3 main-content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Sidebar toggle logic
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                if (window.innerWidth <= 991.98) {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('compact');
                }
            }

            document.getElementById('sidebarCollapse').addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);

            // Theme toggle
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const icon = themeToggle.querySelector('i');

            // Check local storage
            if(localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            themeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
                if(body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    localStorage.setItem('theme', 'light');
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            });

            @if(in_array(auth()->user()->role, ['admin', 'owner', 'superadmin']))
            // Notifications Polling
            function fetchNotifications() {
                fetch('/admin/notifications/unread')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notificationBadge');
                        const list = document.getElementById('notificationList');
                        
                        if (!badge || !list) return;

                        if (data.count > 0) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }

                        let html = '';
                        if (data.notifications && data.notifications.length > 0) {
                            data.notifications.forEach(notif => {
                                const date = new Date(notif.created_at).toLocaleString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'});
                                const orderId = (notif.data && notif.data.order_id) ? notif.data.order_id : null;
                                const targetUrl = orderId ? `/admin/orders/${orderId}` : `/admin/orders`;

                                html += `
                                    <div class="p-3 border-bottom d-flex align-items-start justify-content-between gap-2" style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                                        <a href="${targetUrl}" onclick="markAsReadAndNavigate(${notif.id}, '${targetUrl}', event)" class="text-decoration-none text-start flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold text-primary">${notif.title}</small>
                                                <small class="text-muted" style="font-size: 0.7rem">${date}</small>
                                            </div>
                                            <div class="small text-dark" style="font-size: 0.8rem; line-height: 1.35;">${notif.message}</div>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light border-0 rounded-circle text-muted p-1 ms-1" style="width: 28px; height: 28px;" title="Tandai dibaca" onclick="markAsRead(${notif.id}, event)">
                                            <i class="fas fa-check text-success" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<div class="text-center text-muted py-4 small"><i class="fas fa-bell-slash mb-2 d-block opacity-50" style="font-size: 1.5rem;"></i>Tidak ada notifikasi baru</div>';
                        }
                        list.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            window.markAsRead = function(id, event) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => fetchNotifications());
            };

            window.markAsReadAndNavigate = function(id, url, event) {
                if (event) event.preventDefault();
                fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).finally(() => {
                    window.location.href = url;
                });
            };

            window.markAllAsRead = function(event) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                fetch('/admin/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => fetchNotifications());
            };

            fetchNotifications();
            setInterval(fetchNotifications, 30000); // 30 seconds
            @endif
        });
    </script>
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
