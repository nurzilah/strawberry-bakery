@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <style>
        .home-product-card,
        .home-soft-card {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .home-product-card:hover,
        .home-soft-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .home-product-card .card-img-top {
            aspect-ratio: 1 / 1;
            object-fit: cover;
            padding: 0.35rem;
        }

        .home-product-card .card-body > .flex:last-child {
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .home-hero-visual {
            aspect-ratio: 1 / 1;
            max-width: 20rem;
            width: min(20rem, 82vw);
        }
    </style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="py-5 mb-5" style="background: linear-gradient(135deg, rgba(233, 79, 112, 0.95), rgba(201, 52, 85, 0.95)), url('{{ asset('images/logo.png') }}') center/cover; padding-top: 6rem !important; padding-bottom: 6rem !important; border-radius: 0 0 2rem 2rem; margin-top: -3rem;">
    <div class="container px-4 mt-4">
        <div class="row align-items-center justify-content-between g-5">
            <div class="col-12 col-lg-6 text-white text-center text-lg-start">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm fw-bold border border-white"><i class="fa-solid fa-crown text-warning me-1"></i> Bakery Premium No. 1 di Tegal</span>
                <h1 class="display-4 fw-bold mb-4" style="font-family: 'Playfair Display', serif; line-height: 1.2;">Roti Premium<br>Dipanggang Fresh<br>Setiap Hari</h1>
                <p class="lead mb-5 text-white-50" style="font-weight: 500;">Nikmati kelezatan roti berkualitas premium yang dipanggang fresh dengan bahan pilihan. Pre-order mudah, roti pasti fresh di tangan Anda!</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('products.index') }}" class="btn btn-light text-primary rounded-pill px-5 py-3 fw-bold shadow-sm transition d-flex align-items-center justify-content-center">
                        Belanja Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold transition">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="col-12 col-lg-5 d-none d-lg-block">
                <div class="position-relative d-flex justify-content-center">
                    <div class="bg-white p-2 rounded-circle shadow-lg" style="width: 380px; height: 380px;">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800" alt="Premium Bread" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                    </div>
                    <div class="position-absolute bg-warning text-dark px-4 py-2 rounded-pill fw-bold shadow-lg" style="bottom: 10%; left: 0; transform: rotate(-5deg);">
                        <i class="fa-solid fa-star"></i> Rating 4.9
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
<section class="py-16 bg-white">
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
<section class="py-16 bg-pink-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">
                {{ $isRecommendation ? 'UNTUK KAMU' : 'PRODUK POPULER' }}
            </span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                {{ $isRecommendation ? 'Berdasarkan Pembelian Kamu' : 'Favorit Pelanggan Kami' }}
            </h2>
            <p class="text-gray-600 mt-4">
                {{ $isRecommendation ? 'Kami memilihkan produk ini khusus untuk selera Anda.' : 'Pilihan terbaik yang paling sering dipesan.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($recommendedProducts ?? [] as $product)
            <div class="card home-product-card h-100 bg-white group relative">
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top group-hover:scale-105 transition duration-500">
                    </a>
                    @if($isRecommendation)
                        <span class="badge bg-pink-500 text-white absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold shadow-md">
                            <i class="fas fa-star text-yellow-300 me-1"></i> Rekomendasi
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-sm text-pink-500 font-semibold uppercase">{{ $product->category->name ?? 'Produk' }}</span>
                    <a href="{{ route('products.show', $product->slug) }}" class="block" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                        <h3 class="text-xl font-bold text-gray-800 mt-2 mb-2 hover:text-pink-500 transition">{{ $product->name }}</h3>
                    </a>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-2xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('products.show', $product->slug) }}" 
                           @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif
                           class="btn btn-strawberry btn-sm" aria-label="Lihat detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                Belum ada produk yang tersedia.
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Popular Products -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">PRODUK KAMI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Jelajahi Koleksi Roti Premium</h2>
            <p class="text-gray-600 mt-4">Pilihan lengkap roti untuk setiap momen spesial Anda</p>
        </div>

        <!-- Category Tabs -->
        <div class="flex justify-center space-x-4 mb-12 flex-wrap gap-2">
            <button class="px-6 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-full font-semibold shadow-lg">Semua Produk</button>
            <button class="px-6 py-3 bg-white text-gray-700 rounded-full font-semibold hover:shadow-lg transition">Roti Tawar</button>
            <button class="px-6 py-3 bg-white text-gray-700 rounded-full font-semibold hover:shadow-lg transition">Roti Manis</button>
            <button class="px-6 py-3 bg-white text-gray-700 rounded-full font-semibold hover:shadow-lg transition">Pastry</button>
            <button class="px-6 py-3 bg-white text-gray-700 rounded-full font-semibold hover:shadow-lg transition">Kue Kering</button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products ?? [] as $product)
            <div class="card home-product-card h-100 bg-white group">
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top group-hover:scale-105 transition duration-500">
                    </a>
                    @if($product->is_new)
                        <span class="badge badge-new absolute top-4 right-4">Baru</span>
                    @endif
                    @if($product->is_promo)
                        <span class="badge badge-promo absolute top-4 left-4">Promo</span>
                    @endif
                    <a href="{{ route('products.show', $product->slug) }}" class="absolute bottom-4 right-4 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition hover:bg-pink-500 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                </div>
                <div class="p-6">
                    <span class="text-sm text-pink-500 font-semibold uppercase">{{ $product->category->name }}</span>
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <h3 class="text-xl font-bold text-gray-800 mt-2 mb-2 hover:text-pink-500 transition">{{ $product->name }}</h3>
                    </a>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < floor($product->rating) ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">({{ $product->reviews_count }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-strawberry btn-sm" aria-label="Lihat detail {{ $product->name }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Placeholder products -->
            <div class="card home-product-card h-100 bg-white group">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600" alt="Croissant Butter Premium" class="card-img-top group-hover:scale-105 transition duration-500">
                    <span class="badge badge-featured absolute top-4 right-4">Terlaris</span>
                </div>
                <div class="p-6">
                    <span class="text-sm text-pink-500 font-semibold uppercase">PASTRY</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-2 mb-2">Croissant Butter Premium</h3>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-sm text-gray-600 font-semibold">4.9 (128)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-pink-500">Rp 15.000</span>
                        <button class="btn btn-strawberry btn-sm" aria-label="Tambah Croissant Butter Premium ke keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-block btn-primary">
                Lihat Semua Produk →
            </a>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gradient-to-r from-pink-400 to-red-400 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <h3 class="text-5xl font-bold mb-2">10+</h3>
                <p class="text-pink-100">Tahun Pengalaman</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">50K+</h3>
                <p class="text-pink-100">Pelanggan Puas</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">100+</h3>
                <p class="text-pink-100">Varian Produk</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">4.9</h3>
                <p class="text-pink-100">Rating Pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">TESTIMONI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Apa Kata Pelanggan Kami</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Rotinya enak banget! Teksturnya lembut dan aromanya harum. Sistem pre-order juga memudahkan untuk pesan jauh-jauh hari."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Siti Nurhaliza</h4>
                        <p class="text-sm text-gray-600">Ibu Rumah Tangga</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Pelayanan ramah, pengiriman tepat waktu. Croissant-nya juara! Ga nyesel deh langganan di sini."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Budi Santoso</h4>
                        <p class="text-sm text-gray-600">Pengusaha</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Kualitas terjamin, harga terjangkau. Cocok untuk acara keluarga atau sekadar jadi cemilan. Recommended!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Rina Wijaya</h4>
                        <p class="text-sm text-gray-600">Karyawan Swasta</p>
                    </div>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-block btn-primary">
                Lihat Semua Produk →
            </a>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gradient-to-r from-pink-400 to-red-400 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <h3 class="text-5xl font-bold mb-2">10+</h3>
                <p class="text-pink-100">Tahun Pengalaman</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">50K+</h3>
                <p class="text-pink-100">Pelanggan Puas</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">100+</h3>
                <p class="text-pink-100">Varian Produk</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2">4.9</h3>
                <p class="text-pink-100">Rating Pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">TESTIMONI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Apa Kata Pelanggan Kami</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Rotinya enak banget! Teksturnya lembut dan aromanya harum. Sistem pre-order juga memudahkan untuk pesan jauh-jauh hari."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Siti Nurhaliza</h4>
                        <p class="text-sm text-gray-600">Ibu Rumah Tangga</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Pelayanan ramah, pengiriman tepat waktu. Croissant-nya juara! Ga nyesel deh langganan di sini."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Budi Santoso</h4>
                        <p class="text-sm text-gray-600">Pengusaha</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-8 rounded-2xl">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6">"Kualitas terjamin, harga terjangkau. Cocok untuk acara keluarga atau sekadar jadi cemilan. Recommended!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-300 rounded-full mr-4"></div>
                    <div>
                        <h4 class="font-bold">Rina Wijaya</h4>
                        <p class="text-sm text-gray-600">Karyawan Swasta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-pink-400 to-red-400">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Siap Mencoba Roti Premium Kami?</h2>
        <p class="text-xl mb-8 text-pink-100">Pesan sekarang dan rasakan kelezatan roti segar berkualitas premium</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-white text-pink-500 px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
            Mulai Belanja Sekarang
        </a>
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
</script>
@endpush
@endsection
