@extends('layouts.app')

@section('title', 'Rekomendasi Produk')

@section('content')
    <section class="container">
        <div class="row align-items-end mb-4">
            <div class="col">
                <h1 class="h3 fw-bold mb-2">Rekomendasi Untuk Anda</h1>
                <p class="text-muted mb-0">
                    Rekomendasi dihitung dengan User-Based Collaborative Filtering dan K-Nearest Neighbors K=5.
                </p>
            </div>
        </div>

        @if(empty($recommendations) || count($recommendations) === 0)
            <div class="alert alert-warning mb-0">
                Belum ada rekomendasi produk yang dapat ditampilkan.
            </div>
        @else
            <div class="row g-4">
                @foreach($recommendations as $item)
                    @php
                        $product = $item['product'];
                        $score = $item['score'];
                        $algorithm = $item['algorithm'] ?? 'user_based_cf_knn_k5';
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-4">
                        <article class="card h-100 border-0 shadow-sm">
                            <img
                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.png') }}"
                                class="card-img-top"
                                alt="{{ $product->name }}"
                                style="height: 220px; object-fit: cover;"
                            >
                            <div class="card-body">
                                <span class="badge text-bg-light border mb-2">
                                    {{ $product->category->name ?? 'Produk Bakery' }}
                                </span>
                                <h2 class="h5 fw-bold">{{ $product->name }}</h2>
                                <p class="text-muted small mb-3">{{ Str::limit($product->description, 110) }}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-danger">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="small text-muted">
                                        {{ $product->purchase_count }} pembelian
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                @if($score !== null)
                                    <p class="small text-muted mb-3">
                                        Skor prediksi: <strong>{{ number_format($score, 2) }}</strong>
                                    </p>
                                @else
                                    <p class="small text-muted mb-3">
                                        Ranking: <strong>{{ str_replace('_', ' ', $algorithm) }}</strong>
                                    </p>
                                @endif
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-strawberry w-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
