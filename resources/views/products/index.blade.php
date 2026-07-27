@extends('layouts.app')

@section('title', 'Katalog Produk')

@push('styles')
    <style>
        .catalog-page {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .catalog-hero {
            margin-top: -3rem;
            padding: 4.5rem 0 3.5rem;
        }

        .catalog-hero__title {
            color: #ffffff;
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 700;
            line-height: 1.12;
            margin-bottom: 0;
        }

        .catalog-hero__subtitle {
            color: rgba(255, 255, 255, 0.88);
            font-size: 1rem;
            line-height: 1.75;
            margin-bottom: 0;
            max-width: 45rem;
        }

        .catalog-hero__badge {
            background-color: rgba(255, 255, 255, 0.94);
            color: var(--sb-primary);
            font-weight: 700;
        }

        .catalog-content {
            padding: 3rem 0 4rem;
        }

        .catalog-eyebrow {
            color: var(--sb-primary);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .catalog-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 0;
        }

        .catalog-subtitle {
            color: #64748b;
            font-size: 0.92rem;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .recommendation-break {
            background: linear-gradient(135deg, #fff1f4 0%, #ffffff 100%);
            border: 1px dashed var(--sb-primary);
            border-radius: 20px;
            padding: 1.25rem;
        }

        .recommendation-scroll {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 220px; /* Fix the width so it doesn't stretch */
            gap: 1.25rem;
            overflow-x: auto;
            overscroll-behavior-inline: contain;
            padding-bottom: 0.5rem;
            scroll-snap-type: inline mandatory;
        }

        .recommendation-scroll > * {
            scroll-snap-align: start;
        }

        .product-card {
            position: relative;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            transform: translateY(-6px);
        }

        .product-card__image {
            aspect-ratio: 1 / 1;
            background-color: #fff1f4;
            border-radius: 20px 20px 0 0;
            object-fit: cover;
            width: 100%;
        }

        .product-card__body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 1rem;
        }

        .product-card__category {
            align-self: flex-start;
            background-color: var(--sb-primary-soft);
            border: 1px solid rgba(233, 79, 112, 0.18);
            color: var(--sb-primary);
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 0.65rem;
        }

        .product-card__title {
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            color: #0f172a;
            display: -webkit-box;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.5;
            margin-bottom: 0.35rem;
            min-height: 1.5rem;
            overflow: hidden;
        }

        .product-card__description {
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            color: #64748b;
            display: -webkit-box;
            font-size: 0.825rem;
            line-height: 1.58;
            margin-bottom: 0;
            min-height: 2.6rem;
            overflow: hidden;
        }

        .product-card__actions {
            align-items: center;
            display: flex;
            gap: 0.75rem;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 1rem;
        }

        .product-card__price {
            color: var(--sb-primary);
            font-size: 0.98rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .product-card__button {
            border-radius: 999px;
            height: 42px;
            min-width: 92px;
            padding-inline: 1rem;
        }

        .catalog-empty {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            color: #64748b;
            padding: 4rem 2rem;
            text-align: center;
        }

        @media (max-width: 575.98px) {
            .recommendation-break {
                padding: 1rem;
            }

            .catalog-hero {
                margin-top: -2rem;
                padding: 3.25rem 0 2.75rem;
            }

            .product-card__body {
                padding: 0.85rem;
            }

            .product-card__actions {
                align-items: stretch;
                flex-direction: column;
                gap: 0.65rem;
            }

            .product-card__button {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $products = collect($paginatedProducts ?? []);
        $recommendationItems = collect($recommendations ?? [])->take(6);
    @endphp

    <div class="catalog-page">
        <section class="text-white py-5 mb-5" style="background: linear-gradient(135deg, rgba(233, 79, 112, 0.95), rgba(201, 52, 85, 0.95)), url('https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=1200') center/cover; border-radius: 0 0 2rem 2rem; margin-top: -3rem; padding-top: 5rem !important;">
            <div class="container py-4">
                <div class="row align-items-center justify-content-between g-4">
                    <div class="col-12 col-lg-8 text-center text-lg-start">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm fw-bold"><i class="fa-solid fa-cake-candles me-1"></i> Fresh Bakery Tegal</span>
                        <h1 class="display-4 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Katalog Produk Strawberry Cake & Bakery</h1>
                        <p class="lead mb-0 text-white-50 mx-auto mx-lg-0" style="max-width: 600px;">
                            Pilih roti, pastry, dan cake pre-order dengan tampilan grid yang rapi, lembut, dan mudah dipindai.
                        </p>
                    </div>
                    <div class="col-12 col-lg-4 text-center text-lg-end">
                        @if(isset($paginatedProducts))
                            <span class="badge bg-white text-dark rounded-pill px-4 py-3 shadow fs-6 fw-bold border border-light">
                                <i class="fa-solid fa-boxes-stacked text-primary me-2"></i> {{ $paginatedProducts->total() }} produk tersedia
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="catalog-content">
            <div class="container">
                @if($recommendationItems->isNotEmpty())
                    <section class="recommendation-break mb-5" aria-labelledby="recommendation-title">
                        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
                            <div>
                                <span class="catalog-eyebrow">AI Recommendation</span>
                                <h2 id="recommendation-title" class="catalog-title mt-1">Rekomendasi Spesial Untuk Anda</h2>
                            </div>
                            <p class="catalog-subtitle">
                                Berdasarkan pola pembelian dan collaborative filtering.
                            </p>
                        </div>

                        <div class="recommendation-scroll">
                            @foreach($recommendationItems as $item)
                                @php
                                    $product = is_array($item) ? ($item['product'] ?? null) : $item;
                                    $score = is_array($item) ? ($item['score'] ?? null) : null;
                                @endphp

                                @if($product)
                                    <article class="product-card">
                                        <a href="{{ route('products.show', $product->slug) }}" onclick="logRecommendationClick({{ $product->id }})">
                                            <img src="{{ $product->image_default }}" alt="{{ $product->name }}" class="product-card__image" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                                        </a>
                                        <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-2">
                                            @if($product->is_new)
                                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">✨ Baru</span>
                                            @endif
                                            @if($product->is_promo)
                                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🏷 Promo</span>
                                            @endif
                                            @if($product->is_featured)
                                                <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🌟 Best Seller</span>
                                            @endif
                                            @if($product->pickup_date && \Carbon\Carbon::parse($product->pickup_date)->isFuture())
                                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">⏳ Pre-order</span>
                                            @endif
                                        </div>
                                        <div class="product-card__body d-flex flex-column h-100">
                                            <div class="mb-2">
                                                <span class="badge rounded-pill product-card__category bg-pink-100 text-pink-600">
                                                    {{ $product->category->name ?? 'Produk Bakery' }}
                                                </span>
                                            </div>
                                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none" onclick="logRecommendationClick({{ $product->id }})">
                                                <h3 class="product-card__title fw-bold text-dark hover-text-primary">{{ $product->name }}</h3>
                                            </a>
                                            <p class="product-card__description text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ Str::limit($product->description ?? 'Produk segar pilihan Strawberry Cake & Bakery Tegal.', 80) }}
                                            </p>
                                            
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    @if($product->is_promo && $product->original_price)
                                                        <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">Rp {{ number_format($product->original_price, 0, ',', '.') }}</small>
                                                    @endif
                                                    <div class="product-card__price fw-bold" style="color: #d63384; font-size: 1.1rem;">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 w-100">
                                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary rounded-pill d-flex align-items-center justify-content-center shadow-sm flex-grow-1" style="height: 40px; font-size: 0.85rem; transition: all 0.3s ease;" title="Detail Produk" onclick="logRecommendationClick({{ $product->id }})">
                                                        Detail
                                                    </a>
                                                    @auth
                                                    <form action="{{ route('cart.store') }}" method="POST" class="m-0 flex-shrink-0">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; padding: 0; transition: all 0.3s ease;" title="Tambah ke Keranjang">
                                                            <i class="fa-solid fa-cart-plus"></i>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <a href="{{ route('login') }}" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 40px; height: 40px; padding: 0; transition: all 0.3s ease;" title="Login untuk beli">
                                                        <i class="fa-solid fa-cart-plus"></i>
                                                    </a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                    <div>
                        <h2 class="catalog-title">
                            @if(request('category') && request('category') != 'all')
                                {{ collect($categories)->firstWhere('slug', request('category'))['name'] ?? 'Kategori' }}
                            @else
                                Semua Produk
                            @endif
                        </h2>
                        <span class="catalog-subtitle">Temukan produk favorit Anda</span>
                    </div>
                    <form id="search-form" action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                        <!-- Pertahankan filter kategori jika ada -->
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <input type="text" id="search-input" name="search" class="form-control rounded-pill px-4 shadow-sm" placeholder="Cari nama atau rasa..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>Cari</span>
                        </button>
                    </form>
                </div>

                <!-- Kategori Produk -->
                <div class="mb-4 overflow-auto" style="scrollbar-width: thin;">
                    <div class="d-flex gap-2 pb-2" style="white-space: nowrap;">
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $category['slug']])) }}" 
                               class="btn {{ request('category', 'all') == $category['slug'] ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 fw-medium shadow-sm transition">
                                {{ $category['name'] }} 
                                <span class="badge {{ request('category', 'all') == $category['slug'] ? 'bg-white text-primary' : 'bg-primary text-white' }} ms-1 rounded-pill">
                                    {{ $category['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @forelse($paginatedProducts ?? [] as $product)
                        <div class="col">
                            <article class="product-card">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $product->image_default }}" alt="{{ $product->name }}" class="product-card__image" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                                </a>
                                <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-2">
                                    @if($product->is_new)
                                        <span class="badge bg-primary text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">✨ Baru</span>
                                    @endif
                                    @if($product->is_promo)
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🏷 Promo</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🌟 Best Seller</span>
                                    @endif
                                    @if($product->pickup_date && \Carbon\Carbon::parse($product->pickup_date)->isFuture())
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">⏳ Pre-order</span>
                                    @endif
                                </div>
                                <div class="product-card__body d-flex flex-column h-100">
                                    <div class="mb-2">
                                        <span class="badge rounded-pill product-card__category bg-pink-100 text-pink-600">
                                            {{ $product->category->name ?? 'Produk Bakery' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                        <h3 class="product-card__title fw-bold text-dark hover-text-primary">{{ $product->name }}</h3>
                                    </a>
                                    <p class="product-card__description text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($product->description ?? 'Produk segar pilihan Strawberry Cake & Bakery Tegal.', 80) }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="mb-3">
                                            @if($product->is_promo && $product->original_price)
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">Rp {{ number_format($product->original_price, 0, ',', '.') }}</small>
                                            @endif
                                            <div class="product-card__price fw-bold" style="color: #d63384; font-size: 1.1rem;">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary rounded-pill d-flex align-items-center justify-content-center shadow-sm flex-grow-1" style="height: 40px; font-size: 0.85rem; transition: all 0.3s ease;" title="Detail Produk">
                                                Detail
                                            </a>
                                            @auth
                                            <form action="{{ route('cart.store') }}" method="POST" class="m-0 flex-shrink-0">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; padding: 0; transition: all 0.3s ease;" title="Tambah ke Keranjang">
                                                    <i class="fa-solid fa-cart-plus"></i>
                                                </button>
                                            </form>
                                            @else
                                            <a href="{{ route('login') }}" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 40px; height: 40px; padding: 0; transition: all 0.3s ease;" title="Login untuk beli">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="catalog-empty text-center py-5 mx-auto" style="max-width: 600px;">
                                <div class="mb-4 text-pink-300" style="opacity: 0.8;">
                                    <svg class="mx-auto" style="width: 100px; height: 100px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="h4 text-dark fw-bold mb-2">Produk Belum Tersedia</h3>
                                <p class="text-muted mx-auto" style="max-width: 400px; font-size: 0.95rem;">Maaf, sepertinya produk yang Anda cari sedang kosong atau belum ditambahkan ke katalog kami.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if(isset($paginatedProducts))
                    <div class="mt-4">
                        {{ $paginatedProducts->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        let debounceTimer;

        if (searchInput && searchForm) {
            // Pindahkan kursor ke akhir teks setelah reload
            const end = searchInput.value.length;
            searchInput.setSelectionRange(end, end);
            searchInput.focus();

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    searchForm.submit();
                }, 600); // Tunggu 600ms setelah user berhenti mengetik
            });
        }
    });
</script>
@endpush
