@extends('layouts.admin')

@section('title', 'Penyesuaian Stok Produk')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Penyesuaian Stok Produk</h1>
            <p class="text-muted mb-0">Tambahkan atau kurangi jumlah stok untuk produk ini.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Batal
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 max-w-2xl">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.stock.store', $product) }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark">Nama Produk</label>
                        <input type="text" class="form-control" value="{{ $product->name }}" readonly disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Stok Saat Ini</label>
                        <input type="text" class="form-control" value="{{ $product->stock }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Jumlah Penyesuaian Stok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="fas fa-calculator"></i>
                            </span>
                            <input type="number" name="quantity_added" class="form-control border-primary" value="{{ old('quantity_added') }}" required placeholder="Contoh: 50 atau -20">
                        </div>
                        <div class="form-text small text-muted">Gunakan angka negatif (contoh: -10) untuk mengurangi stok.</div>
                        @error('quantity_added')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Keterangan (Opsional)</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Produksi hari Senin 30 Juni">{{ old('notes') }}</textarea>
                        @error('notes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 pt-3 border-top mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                            <i class="fas fa-save me-2"></i>Simpan Stok
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
