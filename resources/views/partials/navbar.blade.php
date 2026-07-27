<!-- Main Navbar Partial -->
<nav class="navbar navbar-expand-lg app-navbar py-2 py-lg-3 shadow-sm sticky-top bg-white">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery Logo" style="height: 50px; mix-blend-mode: multiply; background-color: transparent;">
            <div class="d-flex flex-column">
                <span class="brand-title" style="font-family: 'Playfair Display', serif; color: #0f172a; font-size: 1.4rem;">Strawberry Bakery</span>
                <span class="brand-subtitle" style="color: var(--sb-primary, #e94f70); font-size: 0.7rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">Premium Cake & Pastry</span>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarPartial" aria-controls="mainNavbarPartial" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbarPartial">
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
                    <a href="{{ route('cart.index') }}" class="cart-icon-btn text-decoration-none position-relative" aria-label="Keranjang">
                        <i class="fa-solid fa-bag-shopping fs-5 text-secondary"></i>
                        @php
                            $cartCount = \App\Models\Cart::countItemsForUser(auth()->id());
                        @endphp
                        @if($cartCount > 0)
                            <span class="badge rounded-pill bg-danger cart-badge shadow-sm position-absolute top-0 start-100 translate-middle">{{ $cartCount }}</span>
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
                                <form method="POST" action="{{ route('logout') }}">
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
