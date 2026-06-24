@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-wrapper {
        background-color: transparent;
    }
    .cart-item-card {
        border-radius: 1.25rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .cart-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    .cart-img-wrap {
        background-color: var(--sb-primary-soft);
        border-radius: 1rem;
        padding: 0.5rem;
        height: 120px;
        width: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .cart-img-wrap img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 0.75rem;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .qty-input:focus {
        outline: none;
    }
    .summary-card {
        border-radius: 1.25rem;
        background: linear-gradient(145deg, #ffffff, #fcfcfc);
    }
    .product-title {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: #0f172a;
    }
    .bg-primary-subtle {
        background-color: var(--sb-primary-soft) !important;
    }
</style>
@endpush

@section('content')
<div class="cart-wrapper py-5">
    <div class="container">

        <div class="d-flex align-items-center gap-3 mb-5">
            <div class="bg-white p-3 rounded-circle shadow-sm" style="color: var(--sb-primary)">
                <i class="fa-solid fa-bag-shopping fs-3"></i>
            </div>
            <h1 class="mb-0" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #0f172a;">Keranjang Belanja</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-12 col-lg-8">
                @forelse($carts ?? [] as $cart)
                    @php
                        $product = $cart->detailCart?->product ?? $cart->product;
                        $quantity = $cart->detailCart?->quantity ?? ($cart->quantity ?? 0);
                    @endphp

                    @continue(! $product)

                    <div class="card border-0 shadow-sm cart-item-card mb-4 bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                                <!-- Image -->
                                <div class="cart-img-wrap">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}" alt="{{ $product->name }}">
                                </div>

                                <!-- Details -->
                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="badge rounded-pill mb-2 px-3 py-2 border" style="background-color: #fff1f4; color: var(--sb-primary); border-color: rgba(233, 79, 112, 0.2) !important;">
                                                <i class="fa-solid fa-tag me-1"></i> {{ $product->category->name ?? 'Produk Bakery' }}
                                            </span>
                                            <h3 class="h5 product-title mb-1">{{ $product->name }}</h3>
                                            <p class="text-muted small mb-0"><i class="fa-regular fa-calendar-check me-1"></i> Pickup: {{ $product->pickup_date ? \Carbon\Carbon::parse($product->pickup_date)->format('d F Y') : 'Hari ini' }}</p>
                                        </div>
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Hapus" data-bs-toggle="tooltip" title="Hapus Produk">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-end mt-4">
                                        <div class="fw-bold fs-5" style="color: var(--sb-primary)">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>

                                        <!-- Qty Control -->
                                        <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ $quantity - 1 }}">
                                                <button type="submit" class="btn btn-light rounded-circle qty-btn text-secondary border shadow-sm" {{ $quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </form>

                                            <input type="text" class="qty-input" value="{{ $quantity }}" readonly>

                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">
                                                <button type="submit" class="btn btn-primary rounded-circle qty-btn shadow-sm">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm cart-item-card text-center p-5 bg-white">
                        <div class="mb-4">
                            <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; color: var(--sb-primary)">
                                <i class="fa-solid fa-cart-arrow-down fs-1"></i>
                            </div>
                        </div>
                        <h3 class="product-title mb-3">Keranjang Anda Masih Kosong</h3>
                        <p class="text-muted mb-4">Yuk, mulai pilih roti dan kue favorit Anda. Banyak produk lezat menanti!</p>
                        <div>
                            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 shadow-sm fw-bold">
                                <i class="fa-solid fa-bag-shopping me-2"></i> Mulai Belanja
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Summary -->
            @if(count($carts ?? []) > 0)
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm summary-card sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="product-title mb-4 border-bottom pb-3">Ringkasan Pesanan</h4>

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Subtotal ({{ collect($carts)->sum(fn ($cart) => $cart->detailCart?->quantity ?? ($cart->quantity ?? 0)) }} item)</span>
                            <span class="fw-semibold text-dark">Rp {{ number_format(collect($carts)->sum(fn ($cart) => $cart->subtotal), 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4 text-muted">
                            <span>Diskon</span>
                            <span class="fw-semibold text-success">- Rp 0</span>
                        </div>

                        <!-- Voucher -->
                        <div class="mb-4 pb-4 border-bottom">
                            <label class="form-label text-muted small fw-bold">Punya Kode Voucher?</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan kode" style="border-radius: 8px 0 0 8px !important;">
                                <button class="btn btn-outline-secondary fw-bold text-dark bg-light" type="button" style="border-radius: 0 8px 8px 0 !important;">Terapkan</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fs-5 fw-bold text-dark">Total</span>
                            <span class="fs-4 fw-bold" style="color: var(--sb-primary)">Rp {{ number_format(collect($carts)->sum(fn ($cart) => $cart->subtotal), 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm d-flex justify-content-between align-items-center px-4">
                            <span>Bayar Sekarang</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <div class="text-center mt-4 text-muted small">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> Transaksi Aman & Terenkripsi
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Recommendations -->
        @if(count($carts ?? []) > 0)
        <div class="mt-5 pt-4">
            <h3 class="product-title mb-4">Rekomendasi Spesial Untuk Anda</h3>
            <div class="row g-4">
                @for($i = 0; $i < 4; $i++)
                <div class="col-6 col-md-3">
                    <a href="{{ route('products.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 cart-item-card bg-white">
                            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop" class="card-img-top p-2" style="border-radius: 1rem;" alt="Product">
                            <div class="card-body p-3 text-center">
                                <h6 class="text-dark fw-bold mb-1">Roti Tawar Spesial</h6>
                                <p class="fw-bold mb-0" style="color: var(--sb-primary)">Rp 25.000</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endfor
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
