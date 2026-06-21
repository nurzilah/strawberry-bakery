<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->order_number ?? $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        .container { width: 320px; margin: 0 auto; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #555; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; vertical-align: top; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .logo { width: 180px; height: auto; object-fit: contain; margin-bottom: 6px; }
        .small { font-size: 11px; color: #444; }
    </style>
</head>
<body>
    <div class="container">
        <div class="center">
            @php
                $logoPath = public_path('images/logo.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" class="logo" alt="Logo">
            @endif
            <div class="bold">Strawberry Bakery</div>
            <div>Struk Pembayaran</div>
            <div class="small">{{ $order->created_at->format('d M Y H:i') }}</div>
            <div class="small">Jl. AR. Hakim No.149, Mangkukusuman</div>
            <div class="small">+62 815-7173-138</div>
            <div class="small">info@strawberrybakery.com</div>
            <div class="small">instagram.com/strawberrycaketegal</div>
        </div>

        <div class="line"></div>

        <div>Order: {{ $order->order_number ?? $order->id }}</div>
        <div>No. Antrian: {{ str_pad((string) $order->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div>Pelanggan: {{ $order->user->name ?? '-' }}</div>
        <div>Metode: {{ strtoupper($order->payment_method) }}</div>
        <div>Pickup: {{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-' }}</div>

        <div class="line"></div>

        <table>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? $item->product_name ?? 'Produk' }}</td>
                    <td class="right">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table>
            <tr>
                <td class="bold">Total</td>
                <td class="right bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>
        <div class="center">Terima kasih</div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
