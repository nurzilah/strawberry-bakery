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
            <tr>
                <td>Layanan</td>
                <td>:</td>
                <td>
                    {{ $order->delivery_method === 'delivery' ? 'Diantar (Delivery)' : 'Ambil di Toko' }}
                    @if($order->delivery_method === 'delivery' && $order->distance_km)
                        ({{ $order->distance_km }} km)
                    @endif
                </td>
            </tr>
            @if($order->delivery_method === 'delivery' && $order->delivery_address)
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $order->delivery_address }}</td>
            </tr>
            @endif
            @if($order->requested_date)
            <tr>
                <td>Tanggal Pemesanan</td>
                <td>:</td>
                <td>
                    {{ \Carbon\Carbon::parse($order->requested_date)->format('d/m/Y') }}
                    @if($order->requested_time)
                        {{ \Carbon\Carbon::parse($order->requested_time)->format('H:i') }} WIB
                    @endif
                </td>
            </tr>
            @endif
            @if($order->delivery_method === 'pickup' && $order->pickup_deadline_at)
            <tr>
                <td>Batas Ambil</td>
                <td>:</td>
                <td><strong>{{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('d/m/Y H:i') }} WIB</strong></td>
            </tr>
            @endif
        </table>
    </div>

    @if($order->delivery_method === 'pickup' && $order->pickup_deadline_at)
    <div class="divider"></div>
    <div style="font-size: 9px; border: 1px dashed #888; padding: 4px; margin-bottom: 8px;">
        <strong>PERHATIAN:</strong> Pesanan pickup WAJIB diambil sebelum
        {{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('H:i') }} WIB
        tgl {{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('d/m/Y') }}.
        Lewat batas waktu, toko tidak bertanggung jawab atas pesanan yang tidak diambil.
    </div>
    @endif

    <div class="divider"></div>

    <table style="width: 100%;">
        @if($order->packages && $order->packages->isNotEmpty())
            @foreach($packageGroups ?? [] as $group)
                <tr>
                    <td colspan="2" style="font-weight: bold; background-color: #f0f0f0; padding: 4px 2px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>{{ $group['is_free'] ? 'BOX GRATIS PROMO' : 'BOX CUSTOM' }}</td>
                                <td class="text-right">x {{ $group['quantity'] }} box</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @foreach($group['items'] as $item)
                <tr>
                    <td colspan="2" style="padding: 2px 2px 2px 8px;">
                        <table style="width: 100%;">
                            <tr>
                                <td width="60%">{{ $item['name'] }}</td>
                                <td width="40%" class="text-right">{{ $item['quantity'] }} x {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endforeach
                @if(!empty($group['beverage_type']))
                <tr>
                    <td colspan="2" style="padding: 2px 2px 2px 8px;">
                        {{ $group['beverage_type'] === 'gelas' ? 'Air Mineral Gelas' : 'Air Mineral Botol' }}
                        x {{ $group['quantity'] }} (Rp {{ number_format($group['beverage_price'], 0, ',', '.') }} / box)
                    </td>
                </tr>
                @endif
                <tr>
                    <td colspan="2" style="padding: 2px 2px 4px 8px;">
                        <table style="width: 100%;">
                            @if($group['is_free'])
                            <tr style="font-weight: bold;">
                                <td>Promo</td>
                                <td class="text-right">GRATIS x {{ $group['quantity'] }} box</td>
                            </tr>
                            @else
                            <tr>
                                <td>Harga per box</td>
                                <td class="text-right">Rp {{ number_format($group['unit_price'], 0, ',', '.') }}</td>
                            </tr>
                            <tr style="font-weight: bold;">
                                <td>Subtotal {{ $group['quantity'] }} box</td>
                                <td class="text-right">Rp {{ number_format($group['total_price'], 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            @endforeach
        @else
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
        @endif
    </table>


    <div class="divider"></div>

    <table class="totals font-bold">
        @if((int) $order->discount_amount > 0)
        <tr>
            <td>Diskon Promo</td>
            <td class="text-right">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if((int) $order->voucher_discount > 0)
        <tr>
            <td>Diskon Voucher ({{ $order->voucher_code }})</td>
            <td class="text-right">- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if((int) $order->shipping_cost > 0)
        <tr>
            <td>Ongkir @if($order->distance_km) ({{ $order->distance_km }} km) @endif</td>
            <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if(filled($order->bonus_item_name) && (int) $order->bonus_item_qty > 0)
        <tr>
            <td>Bonus</td>
            <td class="text-right">{{ $order->bonus_item_qty }} {{ $order->bonus_item_name }}</td>
        </tr>
        @endif
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
