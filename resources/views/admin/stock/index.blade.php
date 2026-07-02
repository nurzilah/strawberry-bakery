@extends('layouts.admin')

@section('title', 'Riwayat Manajemen Stok')

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
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Riwayat Manajemen Stok</h1>
            <p class="text-muted mb-0">Pantau riwayat penambahan stok produk.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Produk
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.stock.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Filter Produk</label>
                    <select name="product_id" class="form-select">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Bulan</label>
                    <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 me-2">Filter</button>
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-light rounded-pill px-4">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart -->
    <div class="chart-container mb-4">
        <canvas id="stockChart"></canvas>
    </div>

    <!-- Table -->
    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Produk</th>
                        <th scope="col" class="text-center">Stok Sebelum</th>
                        <th scope="col" class="text-center">Ditambah</th>
                        <th scope="col" class="text-center">Stok Sesudah</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td class="fw-semibold">{{ $history->product->name }}</td>
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
                            <td>{{ $history->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada riwayat penambahan stok.</td>
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

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('stockChart').getContext('2d');
    const chartData = @json($chartData);
    
    const labels = chartData.map(item => item.month);
    const data = chartData.map(item => item.total);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penambahan Stok',
                data: data,
                backgroundColor: 'rgba(236, 72, 153, 0.5)',
                borderColor: 'rgba(236, 72, 153, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Prediksi & Tren Produksi (12 Bulan Terakhir)'
                }
            }
        }
    });
});
</script>
@endpush
