@extends('layouts.admin')

@section('title', 'Tambah Voucher')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Voucher
        </a>
        <h1 class="h3 fw-bold text-dark mt-2 mb-1">Tambah Voucher Baru</h1>
        <p class="text-muted">Buat kode kupon diskon atau gratis ongkir untuk pelanggan saat checkout.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase font-monospace @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: PROMOHEMAT, SPESIAL10" required style="letter-spacing: 1px;">
                        <div class="form-text">Kode unik huruf kapital tanpa spasi yang diketik pelanggan.</div>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Diskon Gajian 20%" required>
                        <div class="form-text">Nama judul yang tampil di daftar voucher pelanggan.</div>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi / Keterangan Singkat</label>
                        <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan secara ringkas syarat & manfaat voucher ini...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-4"><hr class="my-0"></div>
                    <div class="col-12"><h5 class="fw-bold mb-0 text-dark"><i class="fas fa-percentage text-primary me-2"></i>Tipe & Nilai Diskon</h5></div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Diskon <span class="text-danger">*</span></label>
                        <select name="type" id="voucherType" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                            <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Gratis Ongkir (Delivery)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="discountValueLabel">Besar Nilai Diskon <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_value" id="discountValueInput" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', 15) }}" required min="0">
                        <div class="form-text" id="discountValueHelp">Isi persentase potongan (cth: 15 untuk 15%).</div>
                        @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6" id="maxDiscountGroup">
                        <label class="form-label fw-semibold">Maksimal Potongan Diskon (Rp)</label>
                        <input type="number" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount') }}" placeholder="Contoh: 20000">
                        <div class="form-text">Batas maksimal nominal diskon persen (kosongkan jika tanpa batas).</div>
                        @error('max_discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6" id="maxDistanceGroup">
                        <label class="form-label fw-semibold">Maksimal Radius Pengantaran (KM)</label>
                        <input type="number" step="0.1" name="max_distance_km" class="form-control @error('max_distance_km') is-invalid @enderror" value="{{ old('max_distance_km') }}" placeholder="Contoh: 10">
                        <div class="form-text">Batas radius pengiriman Delivery untuk gratis ongkir (kosongkan jika tanpa batas).</div>
                        @error('max_distance_km') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-4"><hr class="my-0"></div>
                    <div class="col-12"><h5 class="fw-bold mb-0 text-dark"><i class="fas fa-filter text-primary me-2"></i>Syarat & Batasan Penggunaan</h5></div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Minimal Subtotal Belanja (Rp)</label>
                        <input type="number" name="min_subtotal" class="form-control @error('min_subtotal') is-invalid @enderror" value="{{ old('min_subtotal', 0) }}" min="0">
                        <div class="form-text">Minimal belanja sebelum voucher bisa digunakan (isi 0 jika tanpa minimal).</div>
                        @error('min_subtotal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Batas Kuota Pemakaian (Kali)</label>
                        <input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror" value="{{ old('quota') }}" placeholder="Kosongkan jika tak terbatas">
                        <div class="form-text">Berapa kali voucher ini dapat digunakan oleh semua pelanggan secara keseluruhan.</div>
                        @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="first_order_only" id="firstOrderCheck" value="1" {{ old('first_order_only') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="firstOrderCheck">
                                Khusus Pengguna Baru (Hanya berlaku untuk pembelian pertama)
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActiveCheck">
                                Aktifkan Voucher Langsung (Tampil di Checkout)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="fas fa-save me-2"></i>Simpan Voucher
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('voucherType');
        const maxDiscountGroup = document.getElementById('maxDiscountGroup');
        const maxDistanceGroup = document.getElementById('maxDistanceGroup');
        const discountValueLabel = document.getElementById('discountValueLabel');
        const discountValueHelp = document.getElementById('discountValueHelp');
        const discountValueInput = document.getElementById('discountValueInput');

        function updateFields() {
            const type = typeSelect.value;
            if (type === 'percentage') {
                maxDiscountGroup.style.display = 'block';
                maxDistanceGroup.style.display = 'none';
                discountValueLabel.innerHTML = 'Besar Diskon Persen (%) <span class="text-danger">*</span>';
                discountValueHelp.textContent = 'Contoh: 15 untuk potongan 15%.';
            } else if (type === 'fixed') {
                maxDiscountGroup.style.display = 'none';
                maxDistanceGroup.style.display = 'none';
                discountValueLabel.innerHTML = 'Nominal Potongan (Rp) <span class="text-danger">*</span>';
                discountValueHelp.textContent = 'Contoh: 25000 untuk potongan Rp 25.000.';
            } else if (type === 'free_shipping') {
                maxDiscountGroup.style.display = 'none';
                maxDistanceGroup.style.display = 'block';
                discountValueLabel.innerHTML = 'Persentase Gratis Ongkir (%) <span class="text-danger">*</span>';
                discountValueHelp.textContent = 'Isi 100 untuk gratis ongkir penuh (100%).';
                if (!discountValueInput.value || discountValueInput.value == '15') {
                    discountValueInput.value = '100';
                }
            }
        }

        typeSelect.addEventListener('change', updateFields);
        updateFields();
    });
</script>
@endpush

