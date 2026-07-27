@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

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

    .proof-link {
        color: #d63384;
        font-weight: 600;
        text-decoration: none;
    }

    .proof-link:hover {
        color: #b02a6f;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Konfirmasi Pembayaran</h1>
            <p class="text-muted mb-0">Verifikasi bukti pembayaran dan kelola status pesanan pelanggan.</p>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label for="status" class="form-label mb-1 fw-semibold text-muted small">Status Pesanan</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting_verification" {{ request('status') == 'waiting_verification' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="date" class="form-label mb-1 fw-semibold text-muted small">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light px-4">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID Invoice</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nama Pelanggan</th>
                        <th scope="col" class="text-end">Total Uang</th>
                        <th scope="col" class="text-center">Status Pembayaran</th>
                        <th scope="col" class="text-center">Status Pesanan</th>
                        <th scope="col" class="text-center">Bukti Bayar</th>
                        <th scope="col" class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data pembayaran masuk.</td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-semibold text-dark text-nowrap">
                                    {{ $order->order_number ?? 'INV-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="text-muted text-nowrap">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $order->user->name ?? '-' }}</div>
                                    @if(optional($order->user)->email)
                                        <div class="small text-muted">{{ $order->user->email }}</div>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-dark text-nowrap">
                                    Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($order->payment_status === 'paid')
                                        <span class="badge rounded-pill bg-success-subtle text-success">Lunas (Settlement)</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Pending</span>
                                    @elseif(in_array($order->payment_status, ['failed', 'expired', 'cancelled']))
                                        <span class="badge rounded-pill bg-danger-subtle text-danger">{{ ucfirst($order->payment_status) }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">{{ ucfirst($order->payment_status ?? 'Pending') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($order->status === 'waiting_verification')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Menunggu Konfirmasi</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge rounded-pill bg-primary-subtle text-primary">Siap Diambil</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge rounded-pill bg-success-subtle text-success">Selesai Diambil</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge rounded-pill bg-danger-subtle text-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($order->payment_proof_path)
                                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="proof-link">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            Detail
                                        </a>

                                        @if($order->status === 'waiting_verification')
                                            <form action="{{ route($confirmRoute ?? 'admin.orders.confirm', $order) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status === 'processing')
                                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif

                                        @if(!in_array($order->status, ['completed', 'cancelled'], true))
                                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Batalkan pesanan ini?">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-soft-danger rounded-pill px-3">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
