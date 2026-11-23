@extends('layouts.admin')

@section('title', 'Edit Akun Kurir')

@section('content')
<div class="py-2">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 mt-2">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Edit Data Kurir</h1>
            <p class="text-muted mb-0">Ubah informasi akun, alamat domisili, atau reset password kurir.</p>
        </div>
        <div>
            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm border">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kurir
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.kurir.update', $courier) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        {{-- Hidden dummy inputs to prevent aggressive browser autofill --}}
                        <input type="text" style="display:none" aria-hidden="true">
                        <input type="password" style="display:none" aria-hidden="true">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap Kurir <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $courier->name) }}" required autocomplete="off">
                            @error('name')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Login <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $courier->email) }}" required autocomplete="new-password">
                            @error('email')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small">Digunakan oleh kurir untuk masuk ke Dashboard Kurir.</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 py-2 @error('phone') is-invalid @enderror" value="{{ old('phone', $courier->phone) }}" required autocomplete="off">
                            @error('phone')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small">Nomor WhatsApp aktif untuk menerima penugasan pengantaran.</div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Domisili Kurir <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="address" id="address" rows="3" class="form-control rounded-3 @error('address') is-invalid @enderror" placeholder="Contoh: Jl. Merpati No. 12, Kel. Randugunting, Kota Tegal" autocomplete="off">{{ old('address', $courier->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small">Alamat domisili tempat tinggal kurir untuk pencatatan internal toko.</div>
                        </div>

                        <div class="alert alert-info rounded-3 text-muted small my-3 border-0 bg-info-subtle text-info-emphasis">
                            <i class="fas fa-info-circle me-1"></i> Kosongkan kolom password di bawah ini jika tidak ingin mengubah password akun kurir.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Password Baru (Opsional)</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control rounded-start-3 py-2 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary border border-start-0 rounded-end-3 toggle-password-btn" type="button" data-target="password" title="Lihat Password">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-start-3 py-2" placeholder="Ulangi password baru" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary border border-start-0 rounded-end-3 toggle-password-btn" type="button" data-target="password_confirmation" title="Lihat Konfirmasi Password">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fas fa-save me-1"></i> Perbarui Akun Kurir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.toggle-password-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    icon.classList.add('text-primary');
                    icon.classList.remove('text-muted');
                    this.setAttribute('title', 'Sembunyikan Password');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    icon.classList.remove('text-primary');
                    icon.classList.add('text-muted');
                    this.setAttribute('title', 'Lihat Password');
                }
            });
        });
    });
</script>
@endpush
