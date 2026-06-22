@extends('layouts.owner')

@section('owner_content')
<div class="row mb-4 g-4">
    <!-- Total Revenue Metric -->
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card border-0 rounded-4 h-100" style="background: linear-gradient(135deg, var(--sb-primary) 0%, #ff7e9a 100%); color: #fff; box-shadow: 0 10px 25px rgba(233, 79, 112, 0.25);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;">Omzet Aktif (Bulan Ini)</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                        <small class="text-white-50 mt-2 d-block"><i class="fas fa-arrow-up me-1"></i> Termasuk pesanan diproses & selesai</small>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="fas fa-wallet fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Queue Size Metric -->
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card border-0 rounded-4 h-100 bg-white" style="box-shadow: var(--sb-shadow);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;">Antrean Aktif Real-Time</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($activeQueueSize) }} Pesanan</h3>
                        <small class="text-warning mt-2 d-block fw-bold"><i class="fas fa-clock me-1"></i> Menunggu verifikasi / pending</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #fff9e6; color: #f59e0b;">
                        <i class="fas fa-shopping-basket fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table Log for Transactions -->
<div class="card border-0 rounded-4 bg-white mb-4" style="box-shadow: var(--sb-shadow);">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
        <h5 class="mb-0 fw-bold" style="color: var(--sb-dark);">Log Transaksi Sukses Terbaru</h5>
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">5 Transaksi Terakhir</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">ID Pesanan</th>
                        <th class="py-3 fw-semibold">Pelanggan</th>
                        <th class="py-3 fw-semibold">Waktu Selesai</th>
                        <th class="py-3 fw-semibold">Total Nilai</th>
                        <th class="pe-4 py-3 fw-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSuccessfulOrders as $order)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-dark">{{ $order->order_number ?? 'ORD-'.$order->id }}</span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                                        {{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="fw-medium">{{ $order->user->name ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted text-sm">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3 fw-bold" style="color: var(--sb-primary);">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="pe-4 py-3 text-center">
                                <span class="badge bg-success rounded-pill px-3 py-1 fw-medium"><i class="fas fa-check-circle me-1"></i> Completed</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-25"></i>
                                <p class="mb-0">Belum ada transaksi sukses terbaru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recentSuccessfulOrders->count() > 0)
    <div class="card-footer bg-white border-top text-center py-3 rounded-bottom-4">
        <a href="#" class="text-decoration-none fw-bold" style="color: var(--sb-primary);">Lihat Semua Log Transaksi <i class="fas fa-arrow-right ms-1 text-sm"></i></a>
    </div>
    @endif
</div>
@endsection
