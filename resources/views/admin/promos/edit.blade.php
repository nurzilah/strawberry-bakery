@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">Edit Promo</h1>
        <p class="text-muted">Perbarui aturan diskon atau bonus.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.promos.update', $promo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Promo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $promo->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Promo (Scope) <span class="text-danger">*</span></label>
                        <select name="scope" class="form-select @error('scope') is-invalid @enderror" required>
                            <option value="order" {{ old('scope', $promo->scope) == 'order' ? 'selected' : '' }}>Order Reguler (Berdasarkan jumlah pcs)</option>
                            <option value="package" {{ old('scope', $promo->scope) == 'package' ? 'selected' : '' }}>Custom Box (Berdasarkan jumlah box)</option>
                        </select>
                        @error('scope') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Syarat Min Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="min_qty" class="form-control @error('min_qty') is-invalid @enderror" value="{{ old('min_qty', $promo->min_qty) }}" min="1" required>
                        <div class="form-text">Jumlah minimal item/box di keranjang agar promo aktif.</div>
                        @error('min_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-4"><hr></div>
                    <div class="col-12"><h5 class="fw-bold">Benefit Promo</h5></div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Diskon Persen (%)</label>
                        <input type="number" step="0.01" name="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror" value="{{ old('discount_percent', $promo->discount_percent) }}" min="0" max="100">
                        @error('discount_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gratis Qty</label>
                        <input type="number" name="free_qty" class="form-control @error('free_qty') is-invalid @enderror" value="{{ old('free_qty', $promo->free_qty) }}" min="0">
                        <div class="form-text">Berapa pcs/box termurah yang akan digratiskan.</div>
                        @error('free_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gratis Ongkir (Maks Jarak KM)</label>
                        <input type="number" step="0.1" name="free_ongkir_max_km" class="form-control @error('free_ongkir_max_km') is-invalid @enderror" value="{{ old('free_ongkir_max_km', $promo->free_ongkir_max_km) }}">
                        <div class="form-text">Kosongkan jika tidak ada gratis ongkir. Isi 0 jika tanpa batas.</div>
                        @error('free_ongkir_max_km') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Bonus Item</label>
                        <input type="text" name="bonus_item_name" class="form-control @error('bonus_item_name') is-invalid @enderror" value="{{ old('bonus_item_name', $promo->bonus_item_name) }}" placeholder="Contoh: Air Minum Gelas">
                        <div class="form-text">Bonus berupa catatan untuk customer, tidak mengurangi stok produk.</div>
                        @error('bonus_item_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jumlah Bonus</label>
                        <input type="number" name="bonus_item_qty" class="form-control @error('bonus_item_qty') is-invalid @enderror" value="{{ old('bonus_item_qty', $promo->bonus_item_qty) }}" min="1" placeholder="1">
                        @error('bonus_item_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $promo->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive">Promo Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-2">
                    <a href="{{ route('admin.promos.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Perbarui Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
