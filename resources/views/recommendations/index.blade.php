@extends('layouts.app')

@section('title', 'Rekomendasi Untukmu')

@push('styles')
    <style>
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(214, 51, 132, 0.15);
        }
        .badge-recom {
            background: linear-gradient(135deg, #d63384 0%, #ff6b6b 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(214, 51, 132, 0.3);
        }
        .product-img {
            aspect-ratio: 1/1;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
<div class="bg-pink-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Rekomendasi Untukmu</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                @if($recommendedProducts->isNotEmpty() && !isset($isFallback))
                    Berdasarkan riwayat pembelianmu, kami telah memilihkan produk-produk ini khusus untuk seleramu.
                @else
                    Kami belum memiliki cukup data untuk memberikan rekomendasi personal. Untuk sementara, ini adalah produk-produk terlaris kami!
                @endif
            </p>
        </div>

        <!-- Metrics (Optional display for debugging/testing in skripsi) -->
        @if(auth()->user()->role == 'owner' || env('APP_DEBUG'))
            <div class="bg-white p-4 rounded-xl shadow-sm mb-8 text-sm text-gray-500 flex flex-wrap gap-4 justify-center">
                <span><strong>Precision:</strong> {{ number_format($precision ?? 0, 2) }}%</span>
                <span><strong>Recall:</strong> {{ number_format($recall ?? 0, 2) }}%</span>
                <span><strong>MAE:</strong> {{ number_format($mae ?? 0, 2) }}</span>
                <span><strong>RMSE:</strong> {{ number_format($rmse ?? 0, 2) }}</span>
            </div>
        @endif

        <!-- Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @forelse($recommendedProducts as $product)
            <div class="card product-card bg-white rounded-2xl overflow-hidden relative border border-pink-100">
                <!-- Badge -->
                <span class="badge badge-recom absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold z-10">
                    <i class="fas fa-star text-yellow-300 me-1"></i> Rekomendasi
                </span>

                <!-- Image -->
                <div class="relative overflow-hidden cursor-pointer" onclick="logClickAndRedirect({{ $product->id }}, '{{ route('products.show', $product->slug) }}')">
                    <img src="{{ $product->image_default }}" alt="{{ $product->name }}" class="w-full product-img hover:scale-105 transition duration-500" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                </div>

                <!-- Body -->
                <div class="p-4 md:p-6 flex flex-col justify-between h-full">
                    <div>
                        <span class="text-xs text-pink-500 font-semibold uppercase tracking-wider">{{ $product->category->name ?? 'Produk' }}</span>
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mt-1 mb-2 line-clamp-2 cursor-pointer hover:text-pink-600 transition" onclick="logClickAndRedirect({{ $product->id }}, '{{ route('products.show', $product->slug) }}')">
                            {{ $product->name }}
                        </h3>

                    </div>

                    <div class="mt-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                        <span class="text-lg md:text-xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        
                        <div class="flex gap-2 w-full md:w-auto">
                            <!-- Tombol Keranjang (Form Submit biasa karena butuh POST) -->
                            <form action="{{ route('cart.store') }}" method="POST" class="inline" onsubmit="logClickAndSubmit(event, {{ $product->id }})">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white p-2 rounded-lg transition shadow-md" aria-label="Tambah ke Keranjang" title="Tambah ke Keranjang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </button>
                            </form>

                            <!-- Tombol Detail -->
                            <button type="button" onclick="logClickAndRedirect({{ $product->id }}, '{{ route('products.show', $product->slug) }}')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition" aria-label="Lihat Produk" title="Lihat Produk">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-24 h-24 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Rekomendasi</h3>
                <p class="text-gray-600">Teruslah berbelanja, dan kami akan menampilkan rekomendasi khusus untukmu di sini!</p>
                <a href="{{ route('products.index') }}" class="mt-6 inline-block bg-pink-500 text-white px-6 py-2 rounded-full font-bold hover:bg-pink-600 transition">Lihat Katalog Produk</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    /**
     * Hit endpoint AJAX untuk log lalu redirect ke halaman produk
     */
    function logClickAndRedirect(productId, redirectUrl) {
        fetch(`/recommendations/${productId}/click`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .finally(() => {
            window.location.href = redirectUrl;
        });
    }

    /**
     * Hit endpoint AJAX untuk log lalu submit form keranjang
     */
    function logClickAndSubmit(event, productId) {
        event.preventDefault();
        const form = event.target;
        
        fetch(`/recommendations/${productId}/click`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .finally(() => {
            form.submit();
        });
    }
</script>
@endpush
@endsection
