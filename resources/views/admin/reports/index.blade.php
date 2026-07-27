@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Laporan Penjualan</h1>
            <p class="text-muted mb-0">Rekapitulasi penjualan online dan kasir.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.reports.pdf', request()->all()) }}" class="btn btn-danger rounded-pill px-4">
                <i class="fas fa-file-pdf me-2"></i> Export PDF
            </a>
            <a href="{{ route('owner.reports.excel', request()->all()) }}" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('owner.reports.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-auto">
                    <label class="form-label mb-1 fw-semibold text-muted small">Periode</label>
                    <select name="period" id="periodSelect" class="form-select">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                    </select>
                </div>
                
                <div class="col-auto custom-date-group {{ $period != 'custom' ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-auto custom-date-group {{ $period != 'custom' ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-filter me-1"></i> Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white overflow-hidden" style="position: relative;">
                <div class="card-body p-4 position-relative z-1">
                    <p class="mb-1 text-white-50 fw-semibold">Total Online</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalOnline, 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-shopping-cart position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white overflow-hidden" style="position: relative;">
                <div class="card-body p-4 position-relative z-1">
                    <p class="mb-1 text-white-50 fw-semibold">Total POS / Kasir</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPos, 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-cash-register position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white overflow-hidden" style="position: relative;">
                <div class="card-body p-4 position-relative z-1">
                    <p class="mb-1 text-white-50 fw-semibold">Total Keseluruhan</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalCombined, 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-chart-line position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Grafik Penjualan Harian</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Rekap Harian</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dates->sortByDesc('date') as $row)
                                <tr>
                                    <td class="ps-4 text-nowrap">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                    <td class="text-end pe-4 fw-semibold text-dark text-nowrap">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelect = document.getElementById('periodSelect');
        const customDateGroups = document.querySelectorAll('.custom-date-group');
        
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateGroups.forEach(el => el.classList.remove('d-none'));
            } else {
                customDateGroups.forEach(el => el.classList.add('d-none'));
            }
        });

        // Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');
        const labels = {!! json_encode($dates->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray()) !!};
        const dataOnline = {!! json_encode($dates->pluck('online')->toArray()) !!};
        const dataPos = {!! json_encode($dates->pluck('pos')->toArray()) !!};
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Penjualan Online',
                        data: dataOnline,
                        backgroundColor: '#0d6efd',
                        borderRadius: 4
                    },
                    {
                        label: 'Penjualan POS',
                        data: dataPos,
                        backgroundColor: '#198754',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
