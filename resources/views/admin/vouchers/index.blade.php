@extends('layouts.admin')

@section('title', 'Kelola Voucher')

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
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Voucher</h1>
            <p class="text-muted mb-0">Kelola kupon diskon dan gratis ongkir untuk pelanggan saat checkout.</p>
        </div>

        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Tambah Voucher
        </a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Kode & Nama Voucher</th>
                        <th scope="col">Tipe Diskon</th>
                        <th scope="col">Besar Potongan</th>
                        <th scope="col">Syarat & Ketentuan</th>
                        <th scope="col">Kuota Terpakai</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-pink-subtle text-pink-emphasis border border-pink-subtle font-monospace px-2.5 py-1" style="font-size: 0.85rem; background-color: #fdf2f8; color: #db2777; border-color: #fbcfe8 !important;">
                                        {{ $voucher->code }}
                                    </span>
                                    <span class="fw-bold text-dark">{{ $voucher->name }}</span>
                                </div>
                                @if($voucher->description)
                                    <div class="small text-muted" style="max-width: 320px; font-size: 0.78rem;">
                                        {{ $voucher->description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($voucher->type === 'percentage')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">Persentase</span>
                                @elseif($voucher->type === 'fixed')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Nominal Tetap</span>
                                @elseif($voucher->type === 'free_shipping')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1">Gratis Ongkir</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-2.5 py-1">{{ ucfirst($voucher->type) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    {{ $voucher->formatted_benefit }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted space-y-1" style="font-size: 0.78rem; line-height: 1.4;">
                                    @if($voucher->min_subtotal > 0)
                                        <div><i class="fas fa-shopping-basket text-secondary me-1"></i>Min. Belanja: <strong>Rp {{ number_format($voucher->min_subtotal, 0, ',', '.') }}</strong></div>
                                    @else
                                        <div><i class="fas fa-shopping-basket text-secondary me-1"></i>Min. Belanja: <em>Tanpa minimal</em></div>
                                    @endif

                                    @if($voucher->first_order_only)
                                        <div><i class="fas fa-user-tag text-warning me-1"></i><strong>Khusus Pengguna Baru</strong> (Pesanan ke-1)</div>
                                    @endif

                                    @if($voucher->type === 'free_shipping' && $voucher->max_distance_km)
                                        <div><i class="fas fa-map-marker-alt text-danger me-1"></i>Radius Maks: <strong>{{ $voucher->max_distance_km }} KM</strong> (Metode Delivery)</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark fw-semibold">
                                    {{ $voucher->used_count ?? 0 }} / {{ $voucher->quota ? $voucher->quota . ' kuota' : '∞ Tak Terbatas' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($voucher->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    {{-- Toggle Aktif / Nonaktif --}}
                                    <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" style="font-size: 0.75rem;" title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $voucher->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.75rem;">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Hapus voucher '{{ $voucher->code }}'? Voucher ini tidak akan bisa digunakan lagi oleh pelanggan.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger rounded-pill px-3" style="font-size: 0.75rem;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada voucher yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($vouchers->hasPages())
    <div class="mt-4">
        {{ $vouchers->links() }}
    </div>
    @endif
</div>
@endsection

