@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Laporan Penjualan</h1>
            <p class="text-muted mb-0">Rekapitulasi pendapatan penjualan online dan transaksi kasir (POS).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.reports.pdf', array_filter(['period' => $period, 'month' => $selectedMonth, 'year' => $selectedYear, 'start_date' => request('start_date'), 'end_date' => request('end_date')])) }}" class="btn btn-danger rounded-pill px-4 shadow-sm">
                <i class="fas fa-file-pdf me-2"></i> Export PDF
            </a>
            <a href="{{ route('owner.reports.excel', array_filter(['period' => $period, 'month' => $selectedMonth, 'year' => $selectedYear, 'start_date' => request('start_date'), 'end_date' => request('end_date')])) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('owner.reports.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 fw-semibold text-muted small">Jenis Filter / Periode</label>
                    <select name="period" id="periodSelect" class="form-select rounded-3">
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Filter Bulan & Tahun</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Tanggal (Rentang)</option>
                    </select>
                </div>

                <!-- Dropdown Bulan -->
                <div class="col-md-3 col-sm-6 month-year-group {{ in_array($period, ['custom', 'today', 'week']) ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Pilih Bulan</label>
                    <select name="month" id="monthSelect" class="form-select rounded-3">
                        <option value="all" {{ $selectedMonth === 'all' ? 'selected' : '' }}>-- Semua Bulan (1 Tahun) --</option>
                        @php
                            $monthNames = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($monthNames as $num => $name)
                            <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Tahun -->
                <div class="col-md-2 col-sm-6 month-year-group {{ in_array($period, ['custom', 'today', 'week']) ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Pilih Tahun</label>
                    <select name="year" id="yearSelect" class="form-select rounded-3">
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ (int)$selectedYear === (int)$yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Custom Tanggal -->
                <div class="col-md-3 col-sm-6 custom-date-group {{ $period != 'custom' ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Mulai Tanggal</label>
                    <input type="date" name="start_date" id="startDateInput" class="form-control rounded-3" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 col-sm-6 custom-date-group {{ $period != 'custom' ? 'd-none' : '' }}">
                    <label class="form-label mb-1 fw-semibold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="endDateInput" class="form-control rounded-3" value="{{ request('end_date') }}">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                    @if(request()->hasAny(['period', 'month', 'year', 'start_date', 'end_date']))
                        <a href="{{ route('owner.reports.index') }}" class="btn btn-outline-secondary rounded-3 px-3 ms-1" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
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
                    <div class="small text-white-50 mt-1">
                        Rentang: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
                    </div>
                </div>
                <i class="fas fa-shopping-cart position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white overflow-hidden" style="position: relative;">
                <div class="card-body p-4 position-relative z-1">
                    <p class="mb-1 text-white-50 fw-semibold">Total POS / Kasir</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPos, 0, ',', '.') }}</h3>
                    <div class="small text-white-50 mt-1">
                        Transaksi langsung toko
                    </div>
                </div>
                <i class="fas fa-cash-register position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white overflow-hidden" style="position: relative;">
                <div class="card-body p-4 position-relative z-1">
                    <p class="mb-1 text-white-50 fw-semibold">Total Keseluruhan</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalCombined, 0, ',', '.') }}</h3>
                    <div class="small text-white-50 mt-1">
                        Online + Kasir
                    </div>
                </div>
                <i class="fas fa-chart-line position-absolute" style="font-size: 5rem; right: -10px; bottom: -10px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Grafik Penjualan Harian</h5>
                    <span class="badge bg-light text-muted border">
                        {{ $dates->count() }} Hari Terdata
                    </span>
                </div>
                <div class="card-body p-4">
                    @if($dates->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-chart-bar fa-3x mb-3 text-secondary opacity-50"></i>
                            <p>Tidak ada data penjualan pada rentang periode ini.</p>
                        </div>
                    @else
                        <canvas id="salesChart" height="110"></canvas>
                    @endif
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
                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dates->sortByDesc('date') as $row)
                                <tr>
                                    <td class="ps-4 text-nowrap">
                                        <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">
                                            Online: Rp {{ number_format($row['online'], 0, ',', '.') }} | POS: Rp {{ number_format($row['pos'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-dark text-nowrap">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">Tidak ada transaksi.</td>
                                </tr>
                                @endforelse
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
        const monthYearGroups = document.querySelectorAll('.month-year-group');
        
        function handlePeriodChange() {
            const val = periodSelect.value;
            if (val === 'custom') {
                customDateGroups.forEach(el => el.classList.remove('d-none'));
                monthYearGroups.forEach(el => el.classList.add('d-none'));
            } else if (val === 'month') {
                customDateGroups.forEach(el => el.classList.add('d-none'));
                monthYearGroups.forEach(el => el.classList.remove('d-none'));
            } else {
                // today or week
                customDateGroups.forEach(el => el.classList.add('d-none'));
                monthYearGroups.forEach(el => el.classList.add('d-none'));
            }
        }

        periodSelect.addEventListener('change', handlePeriodChange);

        // Chart.js
        const canvas = document.getElementById('salesChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
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
                            label: 'Penjualan POS / Kasir',
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
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: { display: false }
                        },
                        y: {
                            stacked: true,
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
        }
    });
</script>
@endpush
