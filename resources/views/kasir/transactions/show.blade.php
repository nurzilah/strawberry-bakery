@extends('layouts.admin')

@section('title', 'Detail Transaksi POS')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Informasi Transaksi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted ps-0">No. TRX</td>
                        <td class="text-end pe-0 fw-bold">#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tanggal</td>
                        <td class="text-end pe-0">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Kasir</td>
                        <td class="text-end pe-0">{{ $transaction->user->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Metode Bayar</td>
                        <td class="text-end pe-0 text-uppercase fw-bold text-primary">{{ $transaction->payment_method ?? '-' }}</td>
                    </tr>
                </table>
                <hr>
                <div class="d-grid gap-2">
                    <button onclick="window.open(window.location.origin + '/admin/kasir/pos/receipt/{{ $transaction->id }}', '_blank', 'width=400,height=600')" class="btn btn-outline-primary">
                        <i class="fas fa-print me-1"></i> Cetak Ulang Struk
                    </button>
                    <a href="{{ route('kasir.transactions.index') }}" class="btn btn-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Item Pembelian</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                            <tr>
                                <td class="ps-4">{{ $item->product_name }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end pe-4 text-muted">Total Belanja:</td>
                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end pe-4 text-muted">Diskon:</td>
                                <td class="text-end pe-4 fw-bold text-danger">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end pe-4 text-muted">Grand Total:</td>
                                <td class="text-end pe-4 fw-bold text-primary fs-5">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end pe-4 text-muted">Jumlah Bayar:</td>
                                <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end pe-4 text-muted">Kembalian:</td>
                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($transaction->amount_change, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
