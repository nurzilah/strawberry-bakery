@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4 py-md-5">
    <!-- Header -->
    <div class="mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('profile.show') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Edit Profil</h1>
            <p class="text-muted mb-0">Perbarui informasi akun pribadi Anda</p>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: User Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                <div class="card-body p-4 p-md-5 d-flex flex-column align-items-center justify-content-center">
                    
                    <div class="position-relative mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <h2 class="h5 fw-bold text-dark mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-4 text-break">
                        {{ auth()->user()->email }}
                    </p>

                    <hr class="w-100 mb-4 border-2 opacity-10">

                    <div class="text-start w-100">
                        <p class="small text-muted mb-1">Bergabung sejak</p>
                        <p class="fw-bold mb-0 text-dark">
                            {{ auth()->user()->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column: Edit Form -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    
                    <h3 class="h5 fw-bold mb-4 d-flex align-items-center gap-2 border-bottom pb-3">
                        <i class="fa-solid fa-user-pen text-primary"></i> Edit Informasi Profil
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" 
                                value="{{ old('name', auth()->user()->name) }}" 
                                class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Email</label>
                            <div class="input-group">
                                <input type="email" value="{{ auth()->user()->email }}" class="form-control form-control-lg text-muted bg-light" disabled>
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                            </div>
                            <div class="form-text">Email tidak dapat diubah.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">No. Handphone</label>
                            <input type="text" name="phone" 
                                value="{{ old('phone', auth()->user()->phone) }}" 
                                placeholder="Contoh: 08123456789"
                                class="form-control form-control-lg @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Alamat Lengkap</label>
                            <textarea name="address" rows="4" 
                                placeholder="Masukkan alamat lengkap Anda..."
                                class="form-control form-control-lg @error('address') is-invalid @enderror">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end pt-4 border-top mt-5">
                            <button type="submit" class="btn btn-strawberry rounded-pill px-5 py-2 fw-bold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 text-center">
      <div class="modal-body p-5">
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-question text-primary" style="font-size: 2.5rem;"></i>
            </div>
        </div>
        <h4 class="fw-bold text-dark mb-3">Konfirmasi Perubahan</h4>
        <p class="text-muted mb-4">Anda yakin ingin menyimpan perubahan data profil ini?</p>
        
        <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-strawberry rounded-pill px-4" id="confirmBtn">Ya, Simpan</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmBtn = document.getElementById('confirmBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        modal.show();
    });

    confirmBtn.addEventListener('click', function() {
        form.submit();
    });
});
</script>
@endpush
@endsection
