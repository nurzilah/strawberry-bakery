@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <style>
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .home-soft-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem;
        }

        .home-soft-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
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
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
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

        @media (max-width: 575.98px) {
            .product-card__body {
                padding: 0.85rem;
            }

            .product-card__button {
                width: 100%;
            }
        }

        .home-hero-visual {
            aspect-ratio: 1 / 1;
            max-width: 20rem;
            width: min(20rem, 82vw);
        }
        
        .trust-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        /* Testimonial avatar placeholder */
        .avatar-placeholder {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--sb-primary), var(--sb-primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="py-5 mb-5 fade-up" style="background: linear-gradient(135deg, rgba(233, 79, 112, 0.95), rgba(201, 52, 85, 0.95)), url('{{ asset('images/logo.png') }}') center/cover; padding-top: 6rem !important; padding-bottom: 6rem !important; border-radius: 0 0 2rem 2rem;">
    <div class="container px-4 mt-4">
        <div class="row align-items-center justify-content-between g-5">
            <div class="col-12 col-lg-6 text-white text-center text-lg-start">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm fw-bold border border-white"><i class="fa-solid fa-crown text-warning me-1"></i> Bakery Premium No. 1 di Tegal</span>
                <h1 class="display-4 fw-bold mb-4" style="font-family: 'Playfair Display', serif; line-height: 1.2;">Roti Premium<br>Dipanggang Fresh<br>Setiap Hari</h1>
                <p class="lead mb-5 text-white-50" style="font-weight: 500;">Nikmati kelezatan roti berkualitas premium yang dipanggang fresh dengan bahan pilihan. Pre-order mudah, roti pasti fresh di tangan Anda!</p>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start mb-5">
                    <a href="{{ route('products.index') }}" class="btn btn-light text-primary rounded-pill px-5 py-3 fw-bold shadow-sm transition d-flex align-items-center justify-content-center">
                        Belanja Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold transition">
                        Tentang Kami
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start mt-4">
                    <span class="trust-badge rounded-pill px-3 py-1 text-sm font-medium"><i class="fa-solid fa-check text-white me-1"></i> Fresh Dipanggang</span>
                    <span class="trust-badge rounded-pill px-3 py-1 text-sm font-medium"><i class="fa-solid fa-check text-white me-1"></i> Tanpa Pengawet</span>
                    <span class="trust-badge rounded-pill px-3 py-1 text-sm font-medium"><i class="fa-solid fa-check text-white me-1"></i> 100% Halal</span>
                    <span class="trust-badge rounded-pill px-3 py-1 text-sm font-medium"><i class="fa-solid fa-check text-white me-1"></i> Bisa Pre-order</span>
                </div>
            </div>
            <div class="col-12 col-lg-5 d-none d-lg-block fade-up">
                <div class="position-relative d-flex justify-content-center">
                    <div class="bg-white p-2 rounded-circle shadow-lg" style="width: 380px; height: 380px;">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800" alt="Premium Bread" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                    </div>
                    <div class="position-absolute bg-white text-primary px-4 py-2 rounded-pill fw-bold shadow-lg" style="top: 10%; right: 0; transform: rotate(5deg);">
                        <i class="fa-solid fa-medal"></i> Best Quality
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">FITUR KAMI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Yang Kami Pegang Teguh</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="home-soft-card text-center p-8 rounded-2xl bg-pink-50">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Kualitas Premium</h3>
                <p class="text-gray-600">Kami hanya menggunakan bahan-bahan terbaik untuk menghasilkan roti dengan kualitas premium yang terjamin halal.</p>
            </div>

            <!-- Feature 2 -->
            <div class="home-soft-card text-center p-8 rounded-2xl bg-pink-50">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Dibuat dengan Cinta</h3>
                <p class="text-gray-600">Setiap produk dibuat dengan penuh perhatian dan cinta oleh master baker berpengalaman kami.</p>
            </div>

            <!-- Feature 3 -->
            <div class="home-soft-card text-center p-8 rounded-2xl bg-pink-50">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Fresh Daily</h3>
                <p class="text-gray-600">Kami panggang fresh setiap hari untuk memastikan Anda mendapatkan roti yang paling segar.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recommendations Section -->
@if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
<section class="py-16 bg-pink-50 fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">
                {{ $isRecommendation ? 'UNTUK KAMU' : 'PRODUK POPULER' }}
            </span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                {{ $isRecommendation ? 'Rekomendasi Khusus Untukmu' : 'Favorit Pelanggan Kami' }}
            </h2>
            <p class="text-gray-600 mt-4">
                @if($isRecommendation)
                    Berdasarkan riwayat pembelianmu
                @elseif(auth()->check() && !$hasOrders)
                    Lakukan pembelian pertama untuk mendapatkan rekomendasi personal!
                @else
                    Pilihan terbaik yang paling sering dipesan
                @endif
            </p>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($recommendedProducts as $product)
            <div class="col">
                <article class="product-card h-100">
                    <div class="position-relative">
                        <a href="{{ route('products.show', $product->slug) }}" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}" alt="{{ $product->name }}" class="product-card__image">
                        </a>
                        <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-2">
                            @if($isRecommendation)
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">
                                    ⭐ Rekomendasi AI
                                </span>
                            @endif
                            @if($product->is_new)
                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">✨ Baru</span>
                            @endif
                            @if($product->is_promo)
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🏷 Promo</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__body">
                        <span class="badge rounded-pill product-card__category">
                            {{ $product->category->name ?? 'Produk Bakery' }}
                        </span>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                            <h3 class="product-card__title hover-text-primary">{{ $product->name }}</h3>
                        </a>
                        <p class="product-card__description">
                            {{ Str::limit($product->description ?? 'Produk segar pilihan Strawberry Cake & Bakery Tegal.', 60) }}
                        </p>
                        
                        <div class="product-card__actions">
                            <div>
                                @if($product->is_promo && $product->original_price)
                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">Rp {{ number_format($product->original_price, 0, ',', '.') }}</small>
                                @endif
                                <div class="product-card__price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif
                                   class="btn btn-outline-primary rounded-pill d-flex align-items-center justify-content-center shadow-sm flex-fill" style="height: 38px; font-size: 0.85rem;" title="Detail Produk">
                                    Detail
                                </a>
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="m-0 flex-shrink-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; padding: 0;" title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 38px; height: 38px; padding: 0;" title="Login untuk beli">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Popular Products -->
<section class="py-16 bg-gray-50 fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">PRODUK KAMI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Jelajahi Koleksi Roti Premium</h2>
            <p class="text-gray-600 mt-4">Pilihan lengkap roti untuk setiap momen spesial Anda</p>
        </div>

        <!-- Products Grid -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($products ?? [] as $product)
            <div class="col">
                <article class="product-card h-100">
                    <div class="position-relative">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}" alt="{{ $product->name }}" class="product-card__image">
                        </a>
                        <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-2">
                            @if($product->is_new)
                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">✨ Baru</span>
                            @endif
                            @if($product->is_promo)
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem;">🏷 Promo</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__body">
                        <span class="badge rounded-pill product-card__category">
                            {{ $product->category->name ?? 'Produk Bakery' }}
                        </span>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                            <h3 class="product-card__title hover-text-primary">{{ $product->name }}</h3>
                        </a>
                        <p class="product-card__description">
                            {{ Str::limit($product->description ?? 'Produk segar pilihan Strawberry Cake & Bakery Tegal.', 60) }}
                        </p>
                        
                        <div class="product-card__actions">
                            <div>
                                @if($product->is_promo && $product->original_price)
                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">Rp {{ number_format($product->original_price, 0, ',', '.') }}</small>
                                @endif
                                <div class="product-card__price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary rounded-pill d-flex align-items-center justify-content-center shadow-sm flex-fill" style="height: 38px; font-size: 0.85rem;" title="Detail Produk">
                                    Detail
                                </a>
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="m-0 flex-shrink-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; padding: 0;" title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-strawberry rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 38px; height: 38px; padding: 0;" title="Login untuk beli">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12 text-center text-gray-500 py-8">
                Belum ada produk yang tersedia.
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-8 py-3 font-semibold shadow-md hover:shadow-lg transition">
                Lihat Semua Produk →
            </a>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gradient-to-r from-pink-400 to-red-400 text-white fade-up" id="statistics-section">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="{{ date('Y') - 2020 }}">0</span>+</h3>
                <p class="text-pink-100">Tahun Pengalaman</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="{{ $totalCustomers }}">0</span>+</h3>
                <p class="text-pink-100">Pelanggan Puas</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="{{ $totalProducts }}">0</span>+</h3>
                <p class="text-pink-100">Varian Produk</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">{{ number_format($averageRating, 1) }}</h3>
                <p class="text-pink-100">Rating Pelanggan ({{ $totalReviews }} ulasan)</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-white fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">TESTIMONI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Apa Kata Pelanggan Kami</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestReviews as $review)
            <div class="home-soft-card bg-pink-50 p-8 rounded-2xl border border-pink-100 shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                    @endfor
                </div>
                @if($review->comment)
                <p class="text-gray-600 mb-6 italic leading-relaxed">"{{ $review->comment }}"</p>
                @else
                <p class="text-gray-500 mb-6 italic leading-relaxed text-sm">Memberikan rating bintang {{ $review->rating }} tanpa ulasan teks.</p>
                @endif
                <div class="flex items-center">
                    <div class="avatar-placeholder mr-4">{{ strtoupper(substr($review->user->name ?? 'User', 0, 2)) }}</div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $review->user->name ?? 'Pelanggan' }}</h4>
                        <p class="text-sm text-gray-500">Pelanggan</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500 italic">Belum ada ulasan pelanggan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-gray-50 fade-up">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">BANTUAN</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Pertanyaan yang Sering Diajukan</h2>
        </div>
        
        <div class="accordion" id="faqAccordion">
            <!-- FAQ 1 -->
            <div class="accordion-item border-0 mb-3 shadow-sm rounded-lg overflow-hidden">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button font-bold text-gray-800 focus:ring-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Apakah roti selalu fresh?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-gray-600">
                        Tentu saja! Semua produk roti dan kue kami dipanggang setiap hari di dapur kami. Kami tidak menjual roti sisa kemarin untuk menjamin kualitas dan rasa terbaik.
                    </div>
                </div>
            </div>
            <!-- FAQ 2 -->
            <div class="accordion-item border-0 mb-3 shadow-sm rounded-lg overflow-hidden">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed font-bold text-gray-800 focus:ring-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Apakah menerima pre-order?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-gray-600">
                        Ya, kami menerima pesanan pre-order. Anda bisa memesan melalui website kami atau langsung menghubungi tim kami via WhatsApp.
                    </div>
                </div>
            </div>
            <!-- FAQ 3 -->
            <div class="accordion-item border-0 mb-3 shadow-sm rounded-lg overflow-hidden">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed font-bold text-gray-800 focus:ring-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apakah bisa custom cake?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-gray-600">
                        Sangat bisa! Kami menerima pesanan custom cake untuk acara ulang tahun, pernikahan, atau momen spesial lainnya. Silakan konsultasikan desain yang Anda inginkan via WhatsApp.
                    </div>
                </div>
            </div>
            <!-- FAQ 4 -->
            <div class="accordion-item border-0 mb-3 shadow-sm rounded-lg overflow-hidden">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed font-bold text-gray-800 focus:ring-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Bagaimana cara pemesanan?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-gray-600">
                        Anda dapat langsung memilih produk di halaman katalog, memasukkannya ke keranjang, dan melakukan checkout. Alternatif lain, Anda dapat menghubungi kami via WhatsApp.
                    </div>
                </div>
            </div>
            <!-- FAQ 5 -->
            <div class="accordion-item border-0 shadow-sm rounded-lg overflow-hidden">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed font-bold text-gray-800 focus:ring-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Apakah tersedia layanan delivery?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-gray-600">
                        Ya, kami menyediakan layanan pengantaran (delivery) untuk area sekitar Tegal. Biaya pengantaran akan disesuaikan dengan jarak lokasi Anda.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-pink-400 to-red-400 fade-up">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">🍓 Yuk Nikmati Roti Premium Favoritmu Hari Ini!</h2>
        <p class="text-xl mb-10 text-pink-100 max-w-2xl mx-auto">Pesan sekarang dan rasakan kelezatan roti segar berkualitas premium, dipanggang fresh khusus untuk Anda.</p>
        <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
            <a href="{{ route('products.index') }}" class="btn btn-light text-primary px-8 py-3 rounded-pill font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                Belanja Sekarang
            </a>
            <a href="https://wa.me/628157173118" target="_blank" class="btn btn-outline-light px-8 py-3 rounded-pill font-bold text-lg hover:bg-white hover:text-primary transition duration-300">
                <i class="fa-brands fa-whatsapp me-2"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function logRecommendationClick(productId) {
        fetch(`/recommendations/${productId}/click`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).catch(err => console.error('Error logging click:', err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Fade-up animation observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // CountUp animation
        const countUpObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.count-up');
                    counters.forEach(counter => {
                        const target = +counter.getAttribute('data-target');
                        const duration = 2000;
                        const step = target / (duration / 16);
                        let current = 0;
                        
                        const updateCounter = () => {
                            current += step;
                            if (current < target) {
                                counter.innerText = Math.ceil(current);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCounter();
                    });
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        const statsSection = document.getElementById('statistics-section');
        if (statsSection) countUpObserver.observe(statsSection);
    });
</script>
@endpush
@endsection
