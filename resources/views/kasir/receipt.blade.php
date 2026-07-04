<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaction->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        body {
            font-family: 'Courier Prime', monospace;
            font-size: 12px;
            color: #000;
            background: #e0e0e0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .receipt-container {
            width: 300px;
            background: #fff;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 6px;
        }

        .header h1 {
            font-size: 15px;
            margin: 0 0 4px 0;
        }

        .header p {
            margin: 0 0 3px 0;
            font-size: 11px;
        }

        .details { font-size: 11px; margin-bottom: 10px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table td { padding: 3px 0; vertical-align: top; }

        .item-name { display: block; margin-bottom: 2px; }

        .item-qty-price {
            display: flex;
            justify-content: space-between;
            color: #444;
        }

        .totals { font-size: 11px; }
        .totals td { padding: 2px 0; }
        .grand-total { font-size: 14px; font-weight: bold; }

        .footer { margin-top: 15px; font-size: 11px; }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .btn-print {
            background-color: #d63384;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            transition: 0.2s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            background-color: #b02a6c;
            transform: translateY(-2px);
        }

        @media print {
            body { background: #fff; padding: 0; margin: 0; }
            .receipt-container { width: 300px; box-shadow: none; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page { margin: 0; size: 80mm 297mm; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Struk
    </button>
</div>

<div class="receipt-container">
    <div class="header text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery">
        <h1>STRAWBERRY CAKE & BAKERY</h1>
        <p>Jl. AR. Hakim No.149, Mangkukusuman, Kota Tegal</p>
        <p>WA: +62 815-7173-118</p>
        <p>Instagram: @strawberrycaketegal</p>
        <p>Setiap hari, 08.00 - 21.00 WIB</p>
    </div>

    <div class="divider"></div>

    <div class="details">
        <table>
            <tr>
                <td width="30%">No. TRX</td>
                <td width="5%">:</td>
                <td>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td>{{ $user->name ?? 'System' }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td>:</td>
                <td>{{ $transaction->payment_method }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($items as $item)
        <tr>
            <td colspan="2">
                <span class="item-name">{{ $item->product_name }}</span>
                <div class="item-qty-price">
                    <span>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="totals font-bold">
        @if($transaction->discount > 0)
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="text-right">-{{ number_format($transaction->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td>TOTAL</td>
            <td class="text-right">{{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>TUNAI</td>
            <td class="text-right">{{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>KEMBALI</td>
            <td class="text-right">{{ number_format($transaction->amount_change, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Terima Kasih Atas Kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>
</div>

<script>
    window.onload = function() { window.print(); }
</script>

</body>
</html>
