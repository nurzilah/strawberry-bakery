@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-wrapper {
        background-color: transparent;
    }
    .cart-item-card {
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cart-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08) !important;
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
        border-radius: 20px;
        background: linear-gradient(145deg, #ffffff, #fcfcfc);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }
    .product-title {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: #0f172a;
    }
    .bg-primary-subtle {
        background-color: var(--sb-primary-soft) !important;
    }
    /* Hide up/down arrows for number input */
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .qty-input[type=number] {
        -moz-appearance: textfield;
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
                                        <div>
                                            @if($quantity > 1)
                                            <div class="text-muted small mb-1">
                                                Rp {{ number_format($product->price, 0, ',', '.') }} / item
                                            </div>
                                            @endif
                                            <div class="fw-bold fs-5" style="color: var(--sb-primary)">
                                                Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <!-- Qty Control -->
                                        <div class="d-flex align-items-center bg-light rounded-pill p-1 border shadow-sm" style="transition: all 0.3s ease;">
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0 d-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-light rounded-circle qty-btn text-secondary border shadow-sm btn-minus" data-target="qty-{{ $cart->id }}">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>

                                                <input type="number" name="quantity" id="qty-{{ $cart->id }}" class="qty-input mx-1" value="{{ $quantity }}" min="1" max="{{ $product->stock ?? 999 }}">

                                                <button type="button" class="btn btn-primary rounded-circle qty-btn shadow-sm btn-plus" data-target="qty-{{ $cart->id }}">
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
                            <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; color: var(--sb-primary); opacity: 0.9;">
                                <i class="fa-solid fa-basket-shopping" style="font-size: 3.5rem;"></i>
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

                @if(count($carts ?? []) > 0)
                    <div class="mt-4 mb-2 text-center text-md-start">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i> Tambah Produk Lain
                        </a>
                    </div>
                @endif
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

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold d-flex justify-content-between align-items-center px-4" style="transition: all 0.3s ease;">
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
        @if(count($carts ?? []) > 0 && isset($recommendations) && count($recommendations) > 0)
        <div class="mt-5 pt-4">
            <h3 class="product-title mb-4">Rekomendasi Spesial Untuk Anda</h3>
            <div class="row g-4">
                @foreach($recommendations as $rec)
                <div class="col-6 col-md-3">
                    <a href="{{ route('products.index') }}" class="text-decoration-none" onclick="fetch('{{ url('/recommendations/' . $rec->id . '/click') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })">
                        <div class="card border-0 shadow-sm h-100 cart-item-card bg-white">
                            <img src="{{ Storage::url($rec->image) }}" class="card-img-top p-2" style="border-radius: 1rem; aspect-ratio: 4/3; object-fit: cover;" alt="{{ $rec->name }}">
                            <div class="card-body p-3 text-center">
                                <h6 class="text-dark fw-bold mb-1">{{ $rec->name }}</h6>
                                <p class="text-primary fw-bold mb-0">Rp {{ number_format($rec->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let debounceTimer;
    
    const submitForm = (form) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 600);
    };

    // Handle plus/minus buttons
    document.querySelectorAll('.btn-minus, .btn-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const form = this.closest('form');
            const min = parseInt(input.getAttribute('min')) || 1;
            const max = parseInt(input.getAttribute('max')) || 999;
            let val = parseInt(input.value) || 0;

            if (this.classList.contains('btn-plus')) {
                if (val < max) val++;
            } else {
                if (val > min) val--;
            }
            
            if (input.value !== val) {
                input.value = val;
                submitForm(form);
            }
        });
    });

    // Handle manual input
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            const form = this.closest('form');
            const max = parseInt(this.getAttribute('max')) || 999;
            let val = parseInt(this.value);

            if (isNaN(val) || val < 1) {
                // Tunggu blur jika user sedang hapus/input nol
                return;
            }

            if (val > max) {
                this.value = max;
            }
            
            submitForm(form);
        });

        // Handle blur (kalau kosong / 0)
        input.addEventListener('blur', function() {
            const form = this.closest('form');
            const min = parseInt(this.getAttribute('min')) || 1;
            let val = parseInt(this.value);
            
            if (isNaN(val) || val < min) {
                this.value = min;
                submitForm(form);
            }
        });
    });
});
</script>
@endpush
