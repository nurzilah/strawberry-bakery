<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier Prime', monospace;
            font-size: 11px;
            color: #000;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .receipt {
            width: 280px;
            background: #fff;
            padding: 14px 16px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .divider { border-top: 1px dashed #333; margin: 8px 0; }
        .divider-thick { border-top: 2px solid #333; margin: 8px 0; }
        .header { margin-bottom: 8px; }
        .header img { width: 70px; height: auto; margin-bottom: 4px; }
        .header h1 { font-size: 14px; font-weight: 700; letter-spacing: 0.5px; }
        .header p { font-size: 10px; line-height: 1.4; color: #444; }
        .info-table { width: 100%; font-size: 10px; border-collapse: collapse; }
        .info-table td { padding: 1px 0; }
        .info-table td:last-child { text-align: right; }
        .items-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .items-table th { border-bottom: 1px dashed #333; padding: 4px 0; text-align: left; font-size: 10px; }
        .items-table th.qty { text-align: center; }
        .items-table th.price-col { text-align: right; }
        .items-table td { padding: 3px 0; vertical-align: top; }
        .items-table td.price-col { text-align: right; }
        .items-table td.qty { text-align: center; }
        .items-table .item-name { font-size: 11px; }
        .items-table .item-meta { font-size: 10px; color: #555; }
        .totals-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .totals-table td { padding: 2px 0; }
        .totals-table td:last-child { text-align: right; font-weight: 700; }
        .totals-table .grand-total td { font-size: 14px; font-weight: 900; padding: 4px 0; }
        .totals-table .grand-total td:last-child { color: #000; font-size: 15px; }
        .footer { margin-top: 10px; font-size: 10px; text-align: center; color: #555; }
        .footer p { margin-bottom: 2px; }
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        .btn-print {
            background: #f59e0b; color: #fff; border: none; padding: 10px 20px;
            border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: 0.2s;
            font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 8px;
        }
        .btn-print:hover { background: #d97706; transform: translateY(-2px); }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-refunded { background: #fef2f2; color: #dc2626; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { width: 280px; box-shadow: none; padding: 10px 14px; margin: 0; }
            .no-print { display: none !important; }
            @page { margin: 0; size: 80mm 297mm; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak
        </button>
    </div>

    <div class="receipt">
        <div class="header text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery">
            <h1>STRAWBERRY CAKE & BAKERY</h1>
            <p>Jl. AR. Hakim No.149, Mangkukusuman, Kota Tegal</p>
            <p>WA: 0815-7173-118 | @strawberrycaketegal</p>
            <p>Setiap hari, 08.00 - 21.00 WIB</p>
        </div>

        <div class="divider"></div>

        <table class="info-table">
            <tr><td>No. Invoice</td><td>{{ $transaction->invoice_number }}</td></tr>
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::parse($transaction->paid_at ?? $transaction->created_at)->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Kasir</td><td>{{ $transaction->user->name ?? 'System' }}</td></tr>
            <tr><td>Metode</td><td>{{ $transaction->payment_method }}</td></tr>
            <tr><td>Status</td>
                <td><span class="status-badge status-{{ $transaction->status }}">
                    @switch($transaction->status)
                        @case('completed') LUNAS @break
                        @case('refunded') REFUND @break
                        @case('cancelled') BATAL @break
                        @default {{ ucfirst($transaction->status) }}
                    @endswitch
                </span></td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th class="price-col">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td>
                        <span class="item-name">{{ $item->product_name }}</span>
                        <div class="item-meta">{{ number_format($item->price, 0, ',', '.') }} x {{ $item->qty }}</div>
                    </td>
                    <td class="qty">{{ $item->qty }}</td>
                    <td class="price-col">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-thick"></div>

        <table class="totals-table">
            <tr><td>Subtotal</td><td>{{ number_format($transaction->subtotal ?: $transaction->total, 0, ',', '.') }}</td></tr>
            @if($transaction->discount > 0)
            <tr><td>Diskon</td><td>-{{ number_format($transaction->discount, 0, ',', '.') }}</td></tr>
            @endif
            @if($transaction->tax_rate > 0 && $transaction->tax_amount > 0)
            <tr><td>PPN ({{ $transaction->tax_rate }}%)</td><td>{{ number_format($transaction->tax_amount, 0, ',', '.') }}</td></tr>
            @endif
            @if($transaction->extra_charge > 0)
            <tr><td>{{ $transaction->extra_charge_label ?? 'Biaya Tambahan' }}</td><td>{{ number_format($transaction->extra_charge, 0, ',', '.') }}</td></tr>
            @endif
            @if($transaction->rounding != 0)
            <tr><td>Pembulatan</td><td>{{ number_format($transaction->rounding, 0, ',', '.') }}</td></tr>
            @endif
            <tr class="grand-total"><td>TOTAL</td><td>{{ number_format($transaction->grand_total, 0, ',', '.') }}</td></tr>
            <tr><td>Bayar ({{ $transaction->payment_method }})</td><td>{{ number_format($transaction->amount_paid, 0, ',', '.') }}</td></tr>
            <tr><td>Kembalian</td><td>{{ number_format($transaction->amount_change, 0, ',', '.') }}</td></tr>
        </table>

        @if($transaction->notes)
        <div style="margin-top:6px;font-size:10px;color:#555;border-top:1px dashed #ccc;padding-top:4px;">
            <span class="font-bold">Catatan:</span> {{ $transaction->notes }}
        </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            <p style="margin-top:6px;font-size:9px;color:#999;">{{ $transaction->invoice_number }} | {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>window.onload = function() { setTimeout(function() { window.print(); }, 300); }</script>
</body>
</html>
