@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Rincian pesanan dan item yang dibeli.</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-print me-2"></i> Cetak Struk
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill px-4">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="border-bottom">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->product && $item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product_name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $item->product_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-dark fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Informasi Pesanan</h5>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Status</label>
                        <div>
                            @if($order->status === 'waiting_verification')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'processing')
                                <span class="badge bg-primary">Siap Diambil</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success">Selesai Diambil</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Tanggal Pesanan</label>
                        <div class="fw-semibold">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Tanggal Pengambilan</label>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Pelanggan</label>
                        <div class="fw-semibold">{{ $order->user->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $order->user->email ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Metode Checkout</label>
                        <div class="fw-semibold">{{ strtoupper($order->payment_method) }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Status Pembayaran</label>
                        <div>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->payment_status === 'failed' || $order->payment_status === 'expired' || $order->payment_status === 'cancelled')
                                <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </div>
                    </div>
                    @if($order->payment_type)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Tipe Pembayaran (Midtrans)</label>
                        <div class="fw-semibold">{{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}</div>
                    </div>
                    @endif
                    @if($order->midtrans_transaction_id)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Transaction ID</label>
                        <div class="fw-semibold" style="word-break: break-all;">{{ $order->midtrans_transaction_id }}</div>
                    </div>
                    @endif
                    @if($order->paid_at)
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Waktu Pembayaran</label>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y H:i:s') }}</div>
                    </div>
                    @endif

                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">Update Status</h5>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="waiting_verification" {{ $order->status == 'waiting_verification' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Siap Diambil</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
