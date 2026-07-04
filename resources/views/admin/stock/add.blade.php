@extends('layouts.admin')

@section('title', 'Penambahan Stok Produk')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Penambahan Stok Produk</h1>
            <p class="text-muted mb-0">Tambahkan jumlah stok untuk produk.</p>
        </div>
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Batal
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 max-w-2xl">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.stock.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark">Nama Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select border-primary" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-stock="{{ $product->stock }}"
                                        {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Stok Saat Ini</label>
                        <input type="text" id="current_stock" class="form-control bg-light" value="0" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Jumlah Penambahan Stok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="fas fa-plus"></i>
                            </span>
                            <input type="number" id="quantity_added" name="quantity_added" class="form-control border-primary" value="{{ old('quantity_added') }}" min="1" required placeholder="Contoh: 50">
                        </div>
                        @error('quantity_added')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Proyeksi Stok Baru</label>
                        <input type="text" id="projected_stock" class="form-control bg-success-subtle text-success fw-bold border-success" value="0" readonly>
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

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productSelect = document.getElementById('product_id');
    const currentStockInput = document.getElementById('current_stock');
    const quantityAddedInput = document.getElementById('quantity_added');
    const projectedStockInput = document.getElementById('projected_stock');

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        let currentStock = 0;
        
        if (selectedOption && selectedOption.value) {
            currentStock = parseInt(selectedOption.dataset.stock) || 0;
            currentStockInput.value = currentStock;
        } else {
            currentStockInput.value = '0';
        }

        const quantityAdded = parseInt(quantityAddedInput.value) || 0;
        projectedStockInput.value = currentStock + quantityAdded;
    }

    productSelect.addEventListener('change', updateStockInfo);
    quantityAddedInput.addEventListener('input', updateStockInfo);

    // Initial calculation on load
    updateStockInfo();
});
</script>
@endpush
