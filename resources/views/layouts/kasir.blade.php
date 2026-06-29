<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Strawberry Bakery') }} - @yield('title', 'Kasir')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sb-crimson: #e94f70;
            --sb-crimson-dark: #c93455;
            --sb-pink-50: #fcf8f9;
            --sb-pink-100: #fff1f4;
            --sb-pink-600: #c93455;
            --sb-slate: #0f172a;
            --sb-muted: #64748b;
            --sb-border: #e5e7eb;
            --kasir-sidebar-width: 270px;
        }

        body {
            background: #f8fafc;
            color: #1f2937;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .kasir-wrapper {
            min-height: 100vh;
        }

        #kasirSidebar {
            background: var(--sb-pink-50);
            border-right: 1px solid #e8edf3;
            height: 100vh;
            left: 0;
            position: fixed;
            top: 0;
            transition: margin-left 0.25s ease;
            width: var(--kasir-sidebar-width);
            z-index: 1000;
        }

        .kasir-brand {
            border-bottom: 1px solid #e8edf3;
            padding: 1.25rem;
        }

        .kasir-brand img {
            height: 52px;
            object-fit: contain;
            width: auto;
        }

        .kasir-brand-title {
            color: var(--sb-crimson);
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 0.15rem;
        }

        .kasir-brand-subtitle {
            color: var(--sb-muted);
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .kasir-nav {
            display: grid;
            gap: 0.45rem;
            padding: 1rem;
        }

        .kasir-nav a {
            align-items: center;
            border: 1px solid transparent;
            border-radius: 0.9rem;
            color: #334155;
            display: flex;
            font-size: 0.96rem;
            font-weight: 700;
            gap: 0.8rem;
            padding: 0.85rem 0.95rem;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .kasir-nav a i {
            color: var(--sb-crimson);
            text-align: center;
            width: 1.25rem;
        }

        .kasir-nav a:hover,
        .kasir-nav a.active {
            background: #ffffff;
            border-color: rgba(233, 79, 112, 0.22);
            color: var(--sb-crimson-dark);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }

        #kasirContent {
            margin-left: var(--kasir-sidebar-width);
            min-height: 100vh;
        }

        .kasir-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e8edf3;
            min-height: 72px;
            padding: 0.9rem 1.5rem;
        }

        .kasir-page {
            padding: 1.5rem;
        }

        .kasir-avatar {
            align-items: center;
            background: var(--sb-pink-100);
            border-radius: 999px;
            color: var(--sb-crimson);
            display: inline-flex;
            font-weight: 800;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .bg-pink-100 {
            background-color: var(--sb-pink-100) !important;
        }

        .text-pink-600 {
            color: var(--sb-pink-600) !important;
        }

        .btn-kasir-primary {
            background: var(--sb-crimson);
            border-color: var(--sb-crimson);
            color: #ffffff;
            font-weight: 700;
        }

        .btn-kasir-primary:hover,
        .btn-kasir-primary:focus {
            background: var(--sb-crimson-dark);
            border-color: var(--sb-crimson-dark);
            color: #ffffff;
        }

        .kasir-card {
            background: #ffffff;
            border: 1px solid var(--sb-border);
            border-radius: 1rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .w-5 {
            width: 1.25rem !important;
        }

        .h-5 {
            height: 1.25rem !important;
        }

        nav[role="navigation"] svg {
            display: inline-block;
            max-width: 16px;
        }

        @media (max-width: 991.98px) {
            #kasirSidebar {
                margin-left: calc(-1 * var(--kasir-sidebar-width));
            }

            #kasirSidebar.active {
                margin-left: 0;
            }

            #kasirContent {
                margin-left: 0;
            }

            .kasir-page {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="kasir-wrapper">
        <aside id="kasirSidebar" aria-label="Navigasi Kasir">
            <div class="kasir-brand d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SB" style="width: 40px; height: 40px; object-fit: contain;">
                <div class="d-flex flex-column text-start" style="overflow: hidden;">
                    <span class="fw-bold text-truncate" style="font-size: 1rem; line-height: 1.2; color: var(--sb-crimson);">
                        {{ \Illuminate\Support\Str::limit(auth()->user()->name, 15) }}
                    </span>
                    <div class="mt-1">
                        @php
                            $role = auth()->user()->role ?? 'guest';
                            if(in_array($role, ['owner', 'superadmin'])) {
                                $bg = '#d63384';
                                $label = 'OWNER';
                            } elseif($role === 'admin') {
                                $bg = '#0d6efd';
                                $label = 'ADMIN';
                            } elseif($role === 'kasir') {
                                $bg = '#198754';
                                $label = 'KASIR';
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

            <nav class="kasir-nav">
                <a href="{{ route('kasir.pos') }}" class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Beranda / POS</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Antrean Pesanan</span>
                </a>
                <a href="{{ route('kasir.transactions.index') }}" class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Riwayat & Cetak Struk</span>
                </a>
            </nav>
        </aside>

        <div id="kasirContent">
            <header class="kasir-topbar sticky-top">
                <div class="container-fluid d-flex align-items-center gap-3">
                    <button type="button" id="kasirSidebarToggle" class="btn btn-light border d-lg-none" aria-label="Buka navigasi kasir">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div>
                        <div class="fw-bold text-dark">@yield('page_title', 'Workspace Kasir')</div>
                        <div class="small text-muted">{{ now()->format('d M Y, H:i') }} WIB</div>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex">
                            <i class="fa-solid fa-store me-2"></i>Lihat Web
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="kasir-avatar shadow-sm" style="object-fit: cover;" referrerpolicy="no-referrer">
                            @else
                                <span class="kasir-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}</span>
                            @endif
                            <span class="badge bg-pink-100 text-pink-600 rounded-pill px-3 py-2">Kasir Toko</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="kasir-page">
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('kasirSidebarToggle');
            const sidebar = document.getElementById('kasirSidebar');

            if (toggle && sidebar) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
