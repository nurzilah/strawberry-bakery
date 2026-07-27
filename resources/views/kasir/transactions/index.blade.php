@extends('layouts.admin')

@section('title', 'Riwayat Transaksi POS')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Transaksi POS</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kasir.transactions.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-secondary"><i class="fas fa-undo me-1"></i> Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. TRX</th>
                        <th>Tanggal</th>
                        @if(auth()->user()->role !== 'kasir')
                        <th>Kasir</th>
                        @endif
                        <th>Total Items</th>
                        <th>Metode Bayar</th>
                        <th>Total Belanja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="fw-bold">{{ $trx->invoice_number ?? '#TRX-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                        @if(auth()->user()->role !== 'kasir')
                        <td>{{ $trx->user->name ?? 'Unknown' }}</td>
                        @endif
                        <td>{{ $trx->items->sum('qty') }} pcs</td>
                        <td>
                            @if($trx->payment_method == 'cash')
                                <span class="badge bg-success">Cash</span>
                            @elseif($trx->payment_method == 'qris')
                                <span class="badge bg-info">QRIS</span>
                            @else
                                <span class="badge bg-primary">{{ ucfirst($trx->payment_method ?? 'Unknown') }}</span>
                            @endif
                        </td>
                        <td class="fw-bold text-primary">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                        <td>
                            @if($trx->status == 'completed')
                                <span class="badge bg-success">Berhasil</span>
                            @elseif($trx->status == 'refunded' || $trx->status == 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($trx->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('kasir.transactions.show', $trx->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button onclick="window.open(window.location.origin + '/admin/kasir/pos/receipt/{{ $trx->id }}', '_blank', 'width=400,height=600')" class="btn btn-sm btn-secondary" title="Cetak Struk">
                                    <i class="fas fa-print"></i> Struk
                                </button>
                                @if(in_array(auth()->user()->role, ['admin', 'owner', 'superadmin']))
                                    @if($trx->status !== 'refunded' && $trx->status !== 'cancelled')
                                    <form action="{{ route('kasir.transactions.destroy', $trx->id) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Apakah Anda yakin ingin membatalkan transaksi ini (Refund)? Stok produk akan dikembalikan.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Batalkan/Refund">
                                            <i class="fas fa-undo"></i> Refund
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role !== 'kasir' ? 8 : 7 }}" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
