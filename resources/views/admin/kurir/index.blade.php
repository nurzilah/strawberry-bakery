@extends('layouts.admin')

@section('title', 'Kelola Kurir')

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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Akun Kurir</h1>
            <p class="text-muted mb-0">Manajemen staff kurir internal pengantaran pesanan toko.</p>
        </div>
        <div>
            <a href="{{ route('admin.kurir.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Kurir Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama Kurir</th>
                        <th scope="col">Email</th>
                        <th scope="col">No. Telepon / WA</th>
                        <th scope="col" class="text-center">Total Pengiriman</th>
                        <th scope="col" class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($couriers as $courier)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-pink-100 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background-color: #fff1f4; color: var(--sb-primary);">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $courier->name }}</div>
                                        <span class="badge bg-info-subtle text-info rounded-pill" style="font-size: 0.7rem;">Kurir Internal</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $courier->email }}</td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $courier->phone) }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                    <i class="fab fa-whatsapp me-1"></i> {{ $courier->phone }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                    {{ $courier->courierOrders()->count() }} Pesanan
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.kurir.edit', $courier) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.kurir.destroy', $courier) }}" method="POST" class="d-inline form-confirm" data-confirm-message="Hapus akun kurir ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-motorcycle fs-1 mb-3 text-muted opacity-50 d-block"></i>
                                Belum ada akun kurir yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $couriers->links() }}
    </div>
</div>
@endsection
