@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .icon-pink { background: rgba(255, 182, 193, 0.2); color: #d63384; }
    .icon-blue { background: rgba(13, 110, 253, 0.2); color: #0d6efd; }
    .icon-green { background: rgba(25, 135, 84, 0.2); color: #198754; }
    .icon-orange { background: rgba(253, 126, 20, 0.2); color: #fd7e14; }
    .icon-purple { background: rgba(111, 66, 193, 0.2); color: #6f42c1; }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Penjualan Hari Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem;">Penjualan Hari Ini</p>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
                    </div>
                    <div class="stat-icon icon-pink">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Penjualan Bulan Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem;">Penjualan Bulan Ini</p>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($monthSales, 0, ',', '.') }}</h4>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="col-xl-2 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem;">Total Pesanan</p>
                        <h4 class="mb-0 fw-bold">{{ $orderCount }}</h4>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isSuperadmin() || auth()->user()->role === 'admin')
    <!-- Total Produk -->
    <div class="col-xl-2 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem;">Total Produk</p>
                        <h4 class="mb-0 fw-bold">{{ $productCount }}</h4>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->isSuperadmin())
    <!-- Total Customer -->
    <div class="col-xl-2 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem;">Total Customer</p>
                        <h4 class="mb-0 fw-bold">{{ $customerCount }}</h4>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4 mb-4">
    <!-- Grafik Penjualan Harian -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Grafik Penjualan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik Penjualan Bulanan -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Grafik Penjualan (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Pesanan Terbaru -->
    <div class="col-lg-{{ (auth()->user()->isSuperadmin() || auth()->user()->role === 'admin') ? '8' : '12' }}">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Pesanan Terbaru</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No. Order</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="pe-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Diproses</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isSuperadmin() || auth()->user()->role === 'admin')
    <!-- Stok Menipis & Produk Terlaris -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->category->name ?? '-' }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $product->stock }} tersisa</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">Semua stok produk aman.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header py-3">
                <h6 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Produk Terlaris</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($bestSellingProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex justify-content-center align-items-center text-muted" style="width: 40px; height: 40px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->total_sold }} terjual</small>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">Belum ada data penjualan.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->isSuperadmin() || auth()->user()->role === 'admin')
<!-- Statistik Rekomendasi -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light">
                <h6 class="mb-0"><i class="fas fa-magic text-pink-500 me-2"></i>Statistik Sistem Rekomendasi AI</h6>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-primary">{{ $recommendationStats['usage_count'] }}</h3>
                            <small class="text-muted">Kali Digunakan</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-success">{{ $recommendationStats['accuracy'] }}%</h3>
                            <small class="text-muted">Accuracy</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-info">{{ $recommendationStats['precision'] }}%</h3>
                            <small class="text-muted">Precision</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-warning">{{ $recommendationStats['recall'] }}%</h3>
                            <small class="text-muted">Recall</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-danger">{{ $recommendationStats['mae'] }}</h3>
                            <small class="text-muted">MAE</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded">
                            <h3 class="mb-1 text-secondary">{{ $recommendationStats['rmse'] }}</h3>
                            <small class="text-muted">RMSE</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Styling Colors
    const pinkColor = 'rgba(214, 51, 132, 0.8)';
    const pinkBorder = 'rgba(214, 51, 132, 1)';
    const blueColor = 'rgba(13, 110, 253, 0.8)';
    const blueBorder = 'rgba(13, 110, 253, 1)';

    // Daily Sales Chart
    const ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySalesLabels) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($dailySalesData) !!},
                backgroundColor: 'rgba(214, 51, 132, 0.1)',
                borderColor: pinkBorder,
                borderWidth: 2,
                pointBackgroundColor: pinkBorder,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Monthly Sales Chart
    const ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlySalesLabels) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($monthlySalesData) !!},
                backgroundColor: blueColor,
                borderColor: blueBorder,
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush