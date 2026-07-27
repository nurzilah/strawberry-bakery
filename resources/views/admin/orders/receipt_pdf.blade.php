<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan - #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 5px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .header h1 {
            font-size: 14px;
            margin: 0 0 4px 0;
        }

        .header p {
            margin: 0 0 3px 0;
            font-size: 10px;
        }

        .details { font-size: 10px; margin-bottom: 8px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table td { padding: 2px 0; vertical-align: top; }

        .item-name { display: block; margin-bottom: 2px; }

        .item-qty-price {
            width: 100%;
            margin-bottom: 4px;
        }
        
        .item-qty-price table {
            width: 100%;
        }

        .totals { font-size: 10px; }
        .totals td { padding: 2px 0; }
        .grand-total { font-size: 12px; font-weight: bold; }

        .footer { margin-top: 15px; font-size: 10px; }
    </style>
</head>
<body>

<div>
    <div class="header text-center">
        <h1>STRAWBERRY CAKE & BAKERY</h1>
        <p>Jl. AR. Hakim No.149, Mangkukusuman</p>
        <p>Kota Tegal</p>
        <p>WA: +62 815-7173-118</p>
    </div>

    <div class="divider"></div>

    <div class="details">
        <table>
            <tr>
                <td width="30%">No. TRX</td>
                <td width="5%">:</td>
                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pemesan</td>
                <td>:</td>
                <td>{{ $order->user->name ?? 'Pelanggan' }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td>:</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table style="width: 100%;">
        @foreach($order->items as $item)
        <tr>
            <td colspan="2">
                <span class="item-name">{{ $item->product_name }}</span>
                <table style="width: 100%">
                    <tr>
                        <td width="60%">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td width="40%" class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="totals font-bold">
        <tr class="grand-total">
            <td>TOTAL</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Terima Kasih Atas Kunjungan Anda!</p>
        <p>Tumbuh bersama rasa manis.</p>
    </div>
</div>

</body>
</html>
