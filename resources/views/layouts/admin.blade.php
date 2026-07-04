<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Strawberry Bakery') }} - @yield('title', 'Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

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
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #dee2e6;
            transition: all 0.3s;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .dark-mode #sidebar {
            background: #343a40;
            border-right: 1px solid #495057;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #sidebar .sidebar-logo {
            width: 120px;
            height: auto;
            object-fit: contain;
        }

        .dark-mode #sidebar .sidebar-header {
            border-bottom: 1px solid #495057;
        }

        #sidebar ul.components {
            padding: 20px 0;
            flex: 1;
        }

        #sidebar ul p {
            padding: 10px;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.1em;
            display: block;
            color: #495057;
            text-decoration: none;
            transition: 0.2s;
        }

        .dark-mode #sidebar ul li a {
            color: #e9ecef;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #d63384;
            background: var(--pink-pastel);
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }

        .dark-mode #sidebar ul li a:hover, .dark-mode #sidebar ul li.active > a {
            color: #fff;
            background: var(--pink-dark);
        }

        #content {
            width: 100%;
            min-width: 0;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 15px 25px;
            z-index: 1010;
        }

        .dark-mode .navbar-custom {
            background: #343a40;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border-bottom: 1px solid #495057;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
                position: fixed;
            }
            #sidebar.active {
                margin-left: 0;
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
                <img src="{{ asset('images/logo.png') }}" alt="SB" style="width: 40px; height: 40px; object-fit: contain;">
                <div class="d-flex flex-column text-start" style="overflow: hidden;">
                    <span class="fw-bold text-truncate" style="font-size: 1rem; line-height: 1.2;">
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

<ul class="list-unstyled components">

    {{-- ===== OWNER: akses semua ===== --}}
    @if(in_array(auth()->user()->role, ['owner', 'superadmin']))

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-pie me-2"></i> Dashboard
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}">
                <i class="fas fa-box-open me-2"></i> Kelola Produk
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}">
                <i class="fas fa-tags me-2"></i> Kelola Kategori
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
            <a href="{{ route('admin.stock.index') }}">
                <i class="fas fa-boxes me-2"></i> Riwayat Penambahan Stok
            </a>
        </li>
        <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
            <a href="{{ route('kasir.pos') }}">
                <i class="fas fa-cash-register me-2"></i> Mesin POS / Kasir
            </a>
        </li>
        <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
            <a href="{{ route('kasir.transactions.index') }}">
                <i class="fas fa-history me-2"></i> Riwayat Transaksi
            </a>
        </li>
        <li class="{{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
            <a href="{{ route('owner.users.index') }}">
                <i class="fas fa-users me-2"></i> Manajemen Akun
            </a>
        </li>
        <li class="{{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
            <a href="{{ route('owner.reports.index') }}">
                <i class="fas fa-file-invoice-dollar me-2"></i> Laporan Penjualan
            </a>
        </li>

    {{-- ===== ADMIN / IT DEVELOPER ===== --}}
    @elseif(auth()->user()->role === 'admin')

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-pie me-2"></i> Dashboard
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}">
                <i class="fas fa-box-open me-2"></i> Kelola Produk
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}">
                <i class="fas fa-tags me-2"></i> Kelola Kategori
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
            <a href="{{ route('admin.stock.index') }}">
                <i class="fas fa-boxes me-2"></i> Riwayat Penambahan Stok
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart me-2"></i> Pesanan Online
            </a>
        </li>
        <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
            <a href="{{ route('kasir.pos') }}">
                <i class="fas fa-cash-register me-2"></i> Mesin POS / Kasir
            </a>
        </li>
        <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
            <a href="{{ route('kasir.transactions.index') }}">
                <i class="fas fa-history me-2"></i> Riwayat Transaksi POS
            </a>
        </li>
        <li class="{{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
            <a href="{{ route('owner.users.index') }}">
                <i class="fas fa-users me-2"></i> Manajemen Akun
            </a>
        </li>
        <li class="{{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
            <a href="{{ route('owner.reports.index') }}">
                <i class="fas fa-file-invoice-dollar me-2"></i> Laporan Penjualan
            </a>
        </li>

    {{-- ===== KASIR ===== --}}
    @elseif(auth()->user()->role === 'kasir')

        <li class="{{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
            <a href="{{ route('kasir.pos') }}">
                <i class="fas fa-cash-register me-2"></i> Mesin POS / Kasir
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart me-2"></i> Update Pesanan
            </a>
        </li>
        <li class="{{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
            <a href="{{ route('kasir.transactions.index') }}">
                <i class="fas fa-history me-2"></i> Riwayat Transaksi
            </a>
        </li>

    @endif

</ul>


        </nav>

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
                            <button class="btn btn-outline-secondary rounded-circle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notificationBadge">
                                    0
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" id="notificationList" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <li><span class="dropdown-item text-center text-muted py-2">Memuat notifikasi...</span></li>
                            </ul>
                        </div>
                        @endif

                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Lihat Web</a>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
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
            // Sidebar toggle
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });

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
                            badge.textContent = data.count;
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }

                        let html = '';
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(notif => {
                                const date = new Date(notif.created_at).toLocaleString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'});
                                html += `
                                    <li>
                                        <a class="dropdown-item py-2 border-bottom text-wrap" href="#" onclick="markAsRead(${notif.id}, event)">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold text-primary">${notif.title}</small>
                                                <small class="text-muted" style="font-size: 0.7rem">${date}</small>
                                            </div>
                                            <div class="small text-muted" style="white-space: normal;">${notif.message}</div>
                                        </a>
                                    </li>
                                `;
                            });
                            html += `
                                <li>
                                    <a class="dropdown-item text-center fw-bold text-primary py-2 bg-light" href="#" onclick="markAllAsRead(event)">
                                        Tandai semua dibaca
                                    </a>
                                </li>
                            `;
                        } else {
                            html = '<li><span class="dropdown-item text-center text-muted py-3">Tidak ada notifikasi baru</span></li>';
                        }
                        list.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            window.markAsRead = function(id, event) {
                event.preventDefault();
                event.stopPropagation();
                fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => fetchNotifications());
            };

            window.markAllAsRead = function(event) {
                event.preventDefault();
                event.stopPropagation();
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
    @stack('scripts')
</body>
</html>
