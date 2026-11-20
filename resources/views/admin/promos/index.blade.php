@extends('layouts.admin')

@section('title', 'Kelola Promo')

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
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Promo</h1>
            <p class="text-muted mb-0">Kelola aturan diskon dan bonus untuk pesanan.</p>
        </div>

        <a href="{{ route('admin.promos.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Tambah Promo
        </a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama Promo</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Syarat (Min Qty)</th>
                        <th scope="col">Benefit</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $promo->name }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($promo->scope) }}</span>
                            </td>
                            <td>
                                {{ $promo->min_qty }} {{ $promo->scope == 'order' ? 'pcs' : 'box' }}
                            </td>
                            <td>
                                @if($promo->discount_percent)
                                    <div>Diskon {{ $promo->discount_percent }}%</div>
                                @endif
                                @if($promo->free_qty)
                                    <div>Gratis {{ $promo->free_qty }} item</div>
                                @endif
                                @if(!is_null($promo->free_ongkir_max_km))
                                    <div>Free Ongkir ({{ $promo->free_ongkir_max_km == 0 ? 'Tanpa batas' : 'Max '.$promo->free_ongkir_max_km.' km' }})</div>
                                @endif
                                @if($promo->bonus_item_name && $promo->bonus_item_qty)
                                    <div>Bonus {{ $promo->bonus_item_qty }} {{ $promo->bonus_item_name }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($promo->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Hapus promo ini?">
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
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada aturan promo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $promos->links() }}
    </div>
</div>
@endsection
