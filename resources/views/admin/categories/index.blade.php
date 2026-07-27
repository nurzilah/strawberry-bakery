@extends('layouts.admin')

@section('title', 'Kelola Kategori')

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
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Kategori</h1>
            <p class="text-muted mb-0">Kelola pengelompokan produk untuk katalog bakery.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Slug URL</th>
                        <th scope="col" class="text-center">Status Aktif</th>
                        <th scope="col" class="text-end">Tindakan Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $category->name }}</div>
                            </td>
                            <td>
                                <code class="text-muted">{{ $category->slug }}</code>
                            </td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success">Ya</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Hapus kategori ini?">
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
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
