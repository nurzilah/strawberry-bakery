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

        .home-product-card,
        .home-soft-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem;
        }

        .home-product-card:hover,
        .home-soft-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        }

        .home-product-card .card-img-top {
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            transition: transform 0.5s ease;
        }

        .home-product-card:hover .card-img-top {
            transform: scale(1.05);
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
<section class="py-16 bg-pink-50 fade-up">
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
            <div class="card home-product-card h-100 bg-white group overflow-hidden border-0">
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top">
                    </a>
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        @if($isRecommendation)
                            <span class="badge bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                <i class="fas fa-star text-yellow-300 me-1"></i> Rekomendasi
                            </span>
                        @endif
                        @if($product->is_new)
                            <span class="badge bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">✨ Baru</span>
                        @endif
                        @if($product->is_promo)
                            <span class="badge bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">🏷 Promo</span>
                        @endif
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-sm text-pink-500 font-semibold uppercase">{{ $product->category->name ?? 'Produk' }}</span>
                    <a href="{{ route('products.show', $product->slug) }}" class="block mb-4" @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif>
                        <h3 class="text-xl font-bold text-gray-800 mt-2 hover:text-pink-500 transition line-clamp-2">{{ $product->name }}</h3>
                    </a>
                    
                    <div class="mt-auto">
                        @if($product->is_promo && $product->original_price)
                            <div class="text-sm text-gray-400 line-through mb-1">Rp {{ number_format($product->original_price, 0, ',', '.') }}</div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" 
                               @if($isRecommendation) onclick="logRecommendationClick({{ $product->id }})" @endif
                               class="btn btn-strawberry btn-sm rounded-full transition-colors hover:bg-pink-600" aria-label="Lihat detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </a>
                        </div>
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
<section class="py-16 bg-gray-50 fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">PRODUK KAMI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Jelajahi Koleksi Roti Premium</h2>
            <p class="text-gray-600 mt-4">Pilihan lengkap roti untuk setiap momen spesial Anda</p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products ?? [] as $product)
            <div class="card home-product-card h-100 bg-white group border-0">
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top">
                    </a>
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        @if($product->is_new)
                            <span class="badge bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">✨ Baru</span>
                        @endif
                        @if($product->is_promo)
                            <span class="badge bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">🏷 Promo</span>
                        @endif
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-sm text-pink-500 font-semibold uppercase">{{ $product->category->name ?? 'Produk' }}</span>
                    <a href="{{ route('products.show', $product->slug) }}" class="block mb-4">
                        <h3 class="text-xl font-bold text-gray-800 mt-2 hover:text-pink-500 transition line-clamp-2">{{ $product->name }}</h3>
                    </a>
                    
                    <div class="mt-auto">
                        @if($product->is_promo && $product->original_price)
                            <div class="text-sm text-gray-400 line-through mb-1">Rp {{ number_format($product->original_price, 0, ',', '.') }}</div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-strawberry btn-sm rounded-full transition-colors hover:bg-pink-600" aria-label="Lihat detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">
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
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="10">0</span>+</h3>
                <p class="text-pink-100">Tahun Pengalaman</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="50">0</span>K+</h3>
                <p class="text-pink-100">Pelanggan Puas</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold mb-2"><span class="count-up" data-target="100">0</span>+</h3>
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
<section class="py-16 bg-white fade-up">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-pink-500 font-semibold uppercase tracking-wider">TESTIMONI</span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">Apa Kata Pelanggan Kami</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="home-soft-card bg-pink-50 p-8 rounded-2xl border border-pink-100 shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Rotinya enak banget! Teksturnya lembut dan aromanya harum. Sistem pre-order juga memudahkan untuk pesan jauh-jauh hari."</p>
                <div class="flex items-center">
                    <div class="avatar-placeholder mr-4">SN</div>
                    <div>
                        <h4 class="font-bold text-gray-800">Siti Nurhaliza</h4>
                        <p class="text-sm text-gray-500">Ibu Rumah Tangga</p>
                    </div>
                </div>
            </div>

            <div class="home-soft-card bg-pink-50 p-8 rounded-2xl border border-pink-100 shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Pelayanan ramah, pengiriman tepat waktu. Croissant-nya juara! Ga nyesel deh langganan di sini buat acara kantor."</p>
                <div class="flex items-center">
                    <div class="avatar-placeholder mr-4">BS</div>
                    <div>
                        <h4 class="font-bold text-gray-800">Budi Santoso</h4>
                        <p class="text-sm text-gray-500">Pengusaha</p>
                    </div>
                </div>
            </div>

            <div class="home-soft-card bg-pink-50 p-8 rounded-2xl border border-pink-100 shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Kualitas terjamin, harga terjangkau. Cocok untuk acara keluarga atau sekadar jadi cemilan sore sambil ngopi. Recommended!"</p>
                <div class="flex items-center">
                    <div class="avatar-placeholder mr-4">RW</div>
                    <div>
                        <h4 class="font-bold text-gray-800">Rina Wijaya</h4>
                        <p class="text-sm text-gray-500">Karyawan Swasta</p>
                    </div>
                </div>
            </div>
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
            <a href="https://wa.me/628157173138" target="_blank" class="btn btn-outline-light px-8 py-3 rounded-pill font-bold text-lg hover:bg-white hover:text-primary transition duration-300">
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
