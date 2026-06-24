@extends('layouts.admin')

@section('title', 'Kelola Produk')

@push('styles')
<style>
    .admin-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .admin-table-card .table > :not(caption) > * > * {
        padding: 1rem;
        border-bottom-color: #e2e8f0;
        vertical-align: middle;
    }

    .admin-table-card thead th {
        background: #fff1f4;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .product-thumb-frame {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 8px;
        background-color: #fff1f4;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ffe0e8;
    }

    .product-thumb-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-thumb-empty {
        color: #e094a0;
        font-size: 1rem;
        font-weight: 700;
    }

    .products-table {
        min-width: 860px;
    }

    .products-table .actions-cell {
        min-width: 170px;
    }

    .btn-soft-danger {
        color: #dc3545;
        background-color: #fff5f5;
        border-color: #fecaca;
    }

    .btn-soft-danger:hover {
        color: #ffffff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Produk</h1>
            <p class="text-muted mb-0">Atur daftar roti, harga, stok, dan kategori produk.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Tambah Produk
        </a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 products-table">
                <thead>
                    <tr>
                        <th scope="col">Foto</th>
                        <th scope="col">Nama Roti</th>
                        <th scope="col">Kategori</th>
                        <th scope="col" class="text-end">Harga</th>
                        <th scope="col" class="text-center">Stok</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $imageUrl = null;

                            if ($product->image) {
                                if (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])) {
                                    $imageUrl = $product->image;
                                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                                    $imageUrl = asset('storage/' . $product->image);
                                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists('products/' . $product->image)) {
                                    $imageUrl = asset('storage/products/' . $product->image);
                                }
                            }
                        @endphp
                        <tr>
                            <td style="width: 80px;">
                                <div class="product-thumb-frame">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <span class="product-thumb-empty">
                                            <i class="fas fa-image"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                @if($product->slug)
                                    <div class="small text-muted">{{ $product->slug }}</div>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $product->category->name ?? '-' }}
                            </td>
                            <td class="text-end fw-semibold text-dark text-nowrap">
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $product->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger rounded-pill px-3">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <!-- Moderasi Review -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 mt-5">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Moderasi Ulasan</h2>
            <p class="text-muted mb-0">Kelola ulasan dari pelanggan.</p>
        </div>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 products-table">
                <thead>
                    <tr>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Komentar</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReviews as $review)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $review->user->name }}</div>
                            </td>
                            <td>
                                <div class="text-muted">{{ $review->product->name }}</div>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small text-wrap" style="max-width: 300px;">
                                    {{ $review->comment ?? '-' }}
                                </div>
                            </td>
                            <td class="text-muted small">
                                {{ $review->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="actions-cell">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus ulasan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger rounded-pill px-3">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada ulasan dari pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $recentReviews->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
