<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .header { margin-bottom: 30px; text-align: center; }
        .summary { margin-top: 20px; width: 50%; float: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Penjualan Strawberry Bakery</h2>
        <p>
            Periode: 
            @if($period == 'today') Hari Ini ({{ $startDate->format('d M Y') }})
            @elseif($period == 'week') Minggu Ini ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
            @elseif($period == 'month') Bulan Ini ({{ $startDate->format('M Y') }})
            @else {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Penjualan Online</th>
                <th class="text-right">Penjualan POS / Kasir</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dates as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($row['online'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row['pos'], 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($row['total'], 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <table class="summary">
            <tr>
                <th>Total Penjualan Online</th>
                <td class="text-right">Rp {{ number_format($totalOnline, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Penjualan POS / Kasir</th>
                <td class="text-right">Rp {{ number_format($totalPos, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Keseluruhan</th>
                <td class="text-right"><strong>Rp {{ number_format($totalOnline + $totalPos, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

</body>
</html>
