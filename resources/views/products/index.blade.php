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
            grid-auto-columns: minmax(210px, 1fr);
            gap: 1rem;
            overflow-x: auto;
            overscroll-behavior-inline: contain;
            padding-bottom: 0.25rem;
            scroll-snap-type: inline mandatory;
        }

        .recommendation-scroll > * {
            scroll-snap-align: start;
        }

        .product-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .product-card:hover {
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
            transform: translateY(-4px);
        }

        .product-card__image {
            aspect-ratio: 1 / 1;
            background-color: #fff1f4;
            border-radius: 16px 16px 0 0;
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
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            color: #64748b;
            padding: 2rem;
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
                                            <img
                                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}"
                                                class="product-card__image"
                                                alt="{{ $product->name }}"
                                            >
                                        </a>
                                        <div class="product-card__body">
                                            <span class="badge rounded-pill product-card__category">
                                                {{ $product->category->name ?? 'Produk Bakery' }}
                                            </span>
                                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none" onclick="logRecommendationClick({{ $product->id }})">
                                                <h3 class="product-card__title hover-text-primary">{{ $product->name }}</h3>
                                            </a>
                                            <p class="product-card__description">
                                                {{ Str::limit($product->description ?? 'Produk pilihan Strawberry Cake & Bakery Tegal.', 120) }}
                                            </p>
                                            <div class="product-card__actions">
                                                <div>
                                                    <div class="product-card__price">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </div>
                                                    @if($score !== null)
                                                        <small class="text-muted">Skor {{ number_format($score, 2) }}</small>
                                                    @endif
                                                </div>
                                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-strawberry rounded-pill product-card__button">
                                                    Detail
                                                </a>
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
                        <h2 class="catalog-title">Semua Produk</h2>
                        <span class="catalog-subtitle">Temukan produk favorit Anda</span>
                    </div>
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                        <!-- Pertahankan filter kategori jika ada -->
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <input type="text" name="search" class="form-control rounded-pill px-4 shadow-sm" placeholder="Cari nama atau rasa..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari
                        </button>
                    </form>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @forelse($paginatedProducts ?? [] as $product)
                        <div class="col">
                            <article class="product-card">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img
                                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}"
                                        class="product-card__image"
                                        alt="{{ $product->name }}"
                                    >
                                </a>
                                <div class="product-card__body">
                                    <span class="badge rounded-pill product-card__category">
                                        {{ $product->category->name ?? 'Produk Bakery' }}
                                    </span>
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                        <h3 class="product-card__title hover-text-primary">{{ $product->name }}</h3>
                                    </a>
                                    <p class="product-card__description">
                                        {{ Str::limit($product->description ?? 'Produk segar pilihan Strawberry Cake & Bakery Tegal.', 120) }}
                                    </p>
                                    <div class="product-card__actions">
                                        <span class="product-card__price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-strawberry rounded-pill product-card__button">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="catalog-empty">
                                Produk belum tersedia.
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
