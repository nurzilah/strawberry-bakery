@extends('layouts.admin')

@section('title', 'Riwayat Stok: ' . $product->name)

@push('styles')
<style>
    .admin-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        overflow: hidden;
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
            <h1 class="h3 fw-bold text-dark mb-1">Riwayat Stok: {{ $product->name }}</h1>
            <p class="text-muted mb-0">Stok Saat Ini: <strong>{{ $product->stock }}</strong> | Kategori: {{ $product->category->name ?? '-' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.stock.create', $product) }}" class="btn btn-primary rounded-pill px-4 me-2">
                <i class="fas fa-plus me-2"></i>Sesuaikan Stok
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="text-center">Stok Sebelum</th>
                        <th scope="col" class="text-center">Perubahan</th>
                        <th scope="col" class="text-center">Stok Sesudah</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">{{ $history->stock_before }}</td>
                            <td class="text-center fw-bold">
                                @if($history->quantity_added > 0)
                                    <span class="text-success">+{{ $history->quantity_added }}</span>
                                @else
                                    <span class="text-danger">{{ $history->quantity_added }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $history->stock_after }}</td>
                            <td>{{ $history->notes ?? '-' }}</td>
                            <td>{{ $history->user->name ?? 'Unknown' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat stok untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $histories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
