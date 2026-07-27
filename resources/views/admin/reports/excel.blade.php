<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 14px; font-weight: bold; text-align: center;">Laporan Penjualan Strawberry Bakery</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">
                Periode: 
                @if($period == 'today') Hari Ini ({{ $startDate->format('d M Y') }})
                @elseif($period == 'week') Minggu Ini ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
                @elseif($period == 'month') Bulan Ini ({{ $startDate->format('M Y') }})
                @else {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                @endif
            </th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Penjualan Online</th>
            <th>Penjualan POS / Kasir</th>
            <th>Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dates as $row)
        <tr>
            <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
            <td>{{ $row['online'] }}</td>
            <td>{{ $row['pos'] }}</td>
            <td>{{ $row['total'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">Total Penjualan Online:</td>
            <td style="font-weight: bold;">{{ $totalOnline }}</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">Total Penjualan POS / Kasir:</td>
            <td style="font-weight: bold;">{{ $totalPos }}</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">Total Keseluruhan:</td>
            <td style="font-weight: bold;">{{ $totalOnline + $totalPos }}</td>
        </tr>
    </tfoot>
</table>
