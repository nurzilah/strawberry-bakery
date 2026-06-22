@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
    <style>
        .cart-shell {
            background-color: var(--sb-primary-soft);
        }

        .cart-panel {
            background-color: var(--sb-surface);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1rem;
            box-shadow: var(--sb-shadow);
        }

        .cart-row {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .cart-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .cart-image-wrap {
            width: 8rem;
            aspect-ratio: 1 / 1;
            flex: 0 0 auto;
            overflow: hidden;
            border-radius: 0.9rem;
            background-color: var(--sb-primary-soft);
        }

        .cart-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            padding: 0.3rem;
        }

        .cart-icon-button {
            width: 2.5rem;
            height: 2.5rem;
            min-height: 2.5rem;
            padding: 0;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
<div class="cart-shell py-5">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('home') }}" class="hover:text-pink-500">Home</a>
            <span>›</span>
            <span class="text-gray-800 font-medium">Keranjang Belanja</span>
        </div>

        <div class="flex items-center space-x-3 mb-8">
            <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h1 class="text-4xl font-bold text-gray-800">Keranjang Belanja</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($carts ?? [] as $cart)
                    @php
                        $product = $cart->detailCart?->product ?? $cart->product;
                        $quantity = $cart->detailCart?->quantity ?? ($cart->quantity ?? 0);
                    @endphp

                    @continue(! $product)

                    <div class="cart-panel cart-row p-4 p-md-5">
                        <div class="flex items-start space-x-6">
                            <!-- Product Image -->
                            <div class="cart-image-wrap">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}"
                                     alt="{{ $product->name }}"
                                     class="d-block">
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                                        <span class="inline-block px-3 py-1 bg-pink-100 text-pink-600 rounded-full text-xs font-semibold">
                                            {{ $product->category->name ?? 'Produk Bakery' }}
                                        </span>
                                    </div>
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger cart-icon-button" aria-label="Hapus {{ $product->name }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pickup: {{ $product->pickup_date ? \Carbon\Carbon::parse($product->pickup_date)->format('d F Y') : '11 November 2025' }}
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-2xl font-bold text-pink-500">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $quantity - 1 }}">
                                            <button type="submit"
                                                    class="btn btn-outline-secondary cart-icon-button"
                                                    {{ $quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                        </form>

                                        <span class="text-xl font-semibold text-gray-800 w-12 text-center">{{ $quantity }}</span>

                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">
                                            <button type="submit" class="btn btn-strawberry cart-icon-button">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="cart-panel p-5 text-center">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h3>
                        <p class="text-gray-600 mb-6">Yuk, mulai belanja dan temukan produk favorit Anda!</p>
                        <a href="{{ route('products.index') }}" class="inline-block btn-primary">
                            Mulai Belanja
                        </a>
                    </div>
                @endforelse

                <!-- Recommendations -->
                @if(count($carts ?? []) > 0)
                <div class="cart-panel p-4 p-md-5 mt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Pelanggan Lain Juga Membeli</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @for($i = 0; $i < 4; $i++)
                        <div class="group cursor-pointer">
                            <div class="relative overflow-hidden rounded-lg mb-2">
                                <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400" alt="Product" class="w-full h-32 object-cover group-hover:scale-110 transition duration-300">
                            </div>
                            <h4 class="font-semibold text-sm text-gray-800">Baguette Klasik</h4>
                            <p class="text-pink-500 font-bold text-sm">Rp 20.000</p>
                        </div>
                        @endfor
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            @if(count($carts ?? []) > 0)
            <div class="lg:col-span-1">
                <div class="cart-panel p-4 p-md-5 sticky top-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ collect($carts)->sum(fn ($cart) => $cart->detailCart?->quantity ?? ($cart->quantity ?? 0)) }} items)</span>
                            <span class="font-semibold">Rp {{ number_format(collect($carts)->sum(fn ($cart) => $cart->subtotal), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Diskon</span>
                            <span class="font-semibold text-green-600">- Rp 0</span>
                        </div>
                        <hr>
                    </div>

                    <!-- Voucher Code -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode voucher</label>
                        <div class="flex space-x-2">
                            <input type="text"
                                   placeholder="Masukkan kode"
                                   class="form-control flex-1">
                            <button class="btn btn-strawberry rounded-pill px-4">
                                Terapkan
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-bold text-gray-800 mb-6 pt-4 border-t">
                        <span>Total</span>
                        <span class="text-pink-500">Rp {{ number_format(collect($carts)->sum(fn ($cart) => $cart->subtotal), 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-strawberry w-100 rounded-pill py-3">
                        Lanjutkan ke Pembayaran
                    </a>

                    <div class="mt-4 flex items-center justify-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Transaksi aman dan terjamin
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
