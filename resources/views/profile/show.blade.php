@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4 py-md-5">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">Profil Saya</h1>
        <p class="text-muted">Informasi akun pribadi Anda</p>
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
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;" referrerpolicy="no-referrer">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-3 border-white rounded-circle"></span>
                    </div>

                    <h2 class="h4 fw-bold text-dark mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-4 d-flex align-items-center gap-2">
                        <i class="fa-regular fa-envelope"></i> {{ auth()->user()->email }}
                    </p>

                    <hr class="w-25 mx-auto mb-4 border-2 opacity-25 text-primary">

                    <div class="bg-light rounded-3 p-3 w-100">
                        <p class="small text-muted mb-1">Bergabung sejak</p>
                        <p class="fw-bold mb-0 d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-regular fa-calendar text-primary"></i> 
                            {{ auth()->user()->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column: Profile Details -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    
                    <h3 class="h5 fw-bold mb-4 d-flex align-items-center gap-2 border-bottom pb-3">
                        <i class="fa-regular fa-id-card text-primary"></i> Informasi Profil
                    </h3>

                    <div class="row g-4 mb-5">
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                <label class="small text-muted fw-semibold mb-1">Nama Lengkap</label>
                                <p class="mb-0 fw-bold text-dark">{{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                <label class="small text-muted fw-semibold mb-1">Email</label>
                                <p class="mb-0 fw-bold text-dark text-break">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                <label class="small text-muted fw-semibold mb-1">No. Handphone</label>
                                <p class="mb-0 fw-bold text-dark">{{ auth()->user()->phone ?? '-' }}</p>
                                @if(!auth()->user()->phone)
                                    <small class="text-danger fst-italic">Belum diisi</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                <label class="small text-muted fw-semibold mb-1">Alamat Lengkap</label>
                                <p class="mb-0 fw-bold text-dark">{{ auth()->user()->address ?? '-' }}</p>
                                @if(!auth()->user()->address)
                                    <small class="text-danger fst-italic">Belum diisi</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-column flex-md-row gap-3 pt-4 border-top">
                        <a href="{{ route('profile.edit') }}" class="btn btn-strawberry rounded-pill px-4 py-2">
                            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Profil
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="d-inline-block m-0 p-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 w-100">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
