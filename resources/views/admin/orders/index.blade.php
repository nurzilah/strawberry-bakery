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
            <h1 class="h3 fw-bold text-dark mb-1">Konfirmasi Pembayaran & Pesanan</h1>
            <p class="text-muted mb-0">Verifikasi pembayaran, penugasan kurir, dan kelola status pesanan pelanggan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label for="status" class="form-label mb-1 fw-semibold text-muted small">Status Pesanan</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting_verification" {{ request('status') == 'waiting_verification' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Siap Diambil / Diproses</option>
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
                        <th scope="col" class="text-center">Pengiriman & Kurir</th>
                        <th scope="col" class="text-center">Bukti Bayar</th>
                        <th scope="col" class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">Belum ada data pembayaran/pesanan masuk.</td>
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
                                        <span class="badge rounded-pill bg-primary-subtle text-primary">Diproses / Dipanggang</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge rounded-pill bg-success-subtle text-success">Selesai</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge rounded-pill bg-danger-subtle text-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">Pending</span>
                                    @endif
                                    @php
                                        $isOverduePickup = $order->delivery_method === 'pickup'
                                            && $order->pickup_deadline_at
                                            && now()->gt($order->pickup_deadline_at)
                                            && !in_array($order->status, ['completed', 'cancelled']);
                                    @endphp
                                    @if($isOverduePickup)
                                        <span class="badge rounded-pill bg-danger text-white mt-1 d-block"
                                              title="Deadline: {{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('d M Y H:i') }}">
                                            ⚠ Pickup Lewat Deadline
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($order->delivery_method === 'delivery')
                                        <div class="mb-1">
                                            <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                <i class="fas fa-truck me-1"></i> Delivery
                                            </span>
                                        </div>

                                        {{-- Target Tiba & Batas Jam Berangkat Kurir --}}
                                        <div class="my-1 p-2 rounded-3 bg-light border border-light-subtle text-start" style="font-size: 0.72rem; line-height: 1.35; min-width: 175px;">
                                            <div class="text-muted">
                                                <i class="far fa-calendar-alt text-primary me-1"></i> {{ $order->requested_date ? $order->requested_date->format('d/m/Y') : '-' }}
                                            </div>
                                            <div class="fw-bold text-dark mt-0.5">
                                                <i class="far fa-clock text-primary me-1"></i> Target Tiba: {{ $order->formatted_target_time }}
                                            </div>
                                            @if($order->latest_departure_time)
                                                <div class="mt-1">
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 fw-bold d-inline-block text-wrap" style="font-size: 0.68rem; line-height: 1.25;" title="Estimasi tempuh {{ $order->estimated_travel_minutes }} mnt ({{ $order->distance_km }} km) + persiapan 15 mnt">
                                                        <i class="fas fa-motorcycle me-1"></i> Berangkat Maks: {{ $order->latest_departure_time }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($order->distance_km)
                                                <div class="text-muted mt-1" style="font-size: 0.68rem;">
                                                    Jarak: {{ $order->distance_km }} km (±{{ $order->estimated_travel_minutes }} mnt)
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Status Delivery Badge -->
                                        <div class="mb-2">
                                            @if($order->delivery_status === 'dalam_pengantaran' || $order->delivery_status === 'dalam_perjalanan' || $order->delivery_status === 'diambil_kurir' || $order->delivery_status === 'ditugaskan')
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">Dalam Pengantaran</span>
                                            @elseif($order->delivery_status === 'terkirim')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Terkirim</span>
                                            @elseif($order->delivery_status === 'gagal_kirim')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Gagal Kirim</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1">Menunggu Kurir</span>
                                            @endif
                                        </div>

                                        <!-- Assign Courier Dropdown -->
                                        <form action="{{ route('admin.orders.assign-courier', $order) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <select name="courier_id" class="form-select form-select-sm rounded-pill py-1 shadow-sm" onchange="this.form.submit()" style="font-size: 0.75rem; width: 100%; min-width: 200px; padding-left: 0.75rem; padding-right: 2.25rem; text-overflow: ellipsis; cursor: pointer;">
                                                <option value="">-- Tugaskan Kurir --</option>
                                                @foreach($couriers as $courier)
                                                    <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                                        {{ $courier->name }} ({{ $courier->phone ?? '-' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-store me-1"></i> Pickup (Ambil Sendiri)
                                        </span>
                                        @if($order->requested_date || $order->requested_time)
                                            <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                                {{ $order->requested_date ? $order->requested_date->format('d/m/Y') : '' }} {{ $order->formatted_target_time }}
                                            </div>
                                        @endif
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
                                    <div class="d-flex gap-1 justify-content-end">
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
