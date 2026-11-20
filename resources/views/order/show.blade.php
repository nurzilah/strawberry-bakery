@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="text-pink-500 hover:underline">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-gray-600">Tanggal Pesanan</p>
                <p class="font-semibold text-gray-800">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Tanggal Pengambilan/Pengantaran</p>
                <p class="font-semibold text-gray-800">
                    {{ $order->requested_date ? \Carbon\Carbon::parse($order->requested_date)->format('d M Y') : ($order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-') }}
                    @if($order->requested_time)
                        — pukul {{ \Carbon\Carbon::parse($order->requested_time)->format('H:i') }} WIB
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600">Status</p>
                @if($order->status === 'processing')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Siap Diambil</span>
                @elseif($order->status === 'completed')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Selesai</span>
                @elseif($order->status === 'cancelled')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Dibatalkan</span>
                @elseif($order->status === 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Pending</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu Konfirmasi</span>
                @endif
            </div>
            <div>
                <p class="text-gray-600">Metode Pengiriman</p>
                <p class="font-semibold text-gray-800">
                    {{ $order->delivery_method === 'delivery' ? 'Kirim ke Alamat (Delivery)' : 'Ambil di Toko (Pickup)' }}
                </p>
            </div>
            <div>
                <p class="text-gray-600">Total</p>
                <p class="font-semibold text-pink-500 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($order->delivery_method === 'delivery')
            <div class="mb-6 rounded-xl border border-pink-200 bg-pink-50/70 p-4">
                <div class="flex items-start gap-3">
                    <span class="w-9 h-9 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center flex-shrink-0 mt-0.5 font-bold text-sm">
                        <i class="fas fa-truck"></i>
                    </span>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                            <p class="font-bold text-gray-800 mb-0">Informasi Pengiriman (Delivery)</p>
                            <div>
                                @if($order->delivery_status === 'dalam_pengantaran' || $order->delivery_status === 'dalam_perjalanan')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-motorcycle"></i> Sedang Dalam Pengantaran
                                    </span>
                                @elseif($order->delivery_status === 'terkirim')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i> Pesanan Sudah Terkirim
                                    </span>
                                @elseif($order->delivery_status === 'gagal_kirim')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle"></i> Pengantaran Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock"></i> Menunggu Kurir
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-gray-700 mb-1">
                            <strong>Alamat Tujuan:</strong> {{ $order->delivery_address ?? '-' }}
                        </p>
                        @if($order->distance_km)
                            <p class="text-xs text-pink-700 font-semibold mb-2">
                                <i class="fas fa-route me-1"></i> Jarak Tempuh: {{ $order->distance_km }} km dari toko
                            </p>
                        @endif

                        {{-- Info Kurir Pengantar & Kontak WhatsApp --}}
                        @if($order->courier || $order->courier_name)
                            <div class="mt-3 pt-3 border-t border-pink-200/80 flex flex-wrap items-center justify-between gap-3 bg-white/70 p-3 rounded-lg">
                                <div>
                                    <p class="text-xs text-gray-500 font-medium mb-0.5">Kurir Pengantar:</p>
                                    <p class="text-sm font-bold text-gray-800 mb-0 flex items-center gap-1.5">
                                        <i class="fas fa-user-check text-pink-600"></i> {{ $order->courier_display_name }}
                                        @if(optional($order->courier)->vehicle_type)
                                            <span class="text-xs font-normal text-gray-500">({{ $order->courier->vehicle_type }}{{ $order->courier->plate_number ? ' - ' . $order->courier->plate_number : '' }})</span>
                                        @endif
                                    </p>
                                    @if($order->courier_display_phone && $order->courier_display_phone !== '-')
                                        <p class="text-xs text-gray-600 mb-0 mt-0.5">
                                            <i class="fas fa-phone text-gray-400 me-1"></i> {{ $order->courier_display_phone }}
                                        </p>
                                    @endif
                                </div>

                                @if($order->courier_display_phone && $order->courier_display_phone !== '-')
                                    @php
                                        $waNumber = preg_replace('/\D+/', '', $order->courier_display_phone);
                                        if (str_starts_with($waNumber, '0')) {
                                            $waNumber = '62' . substr($waNumber, 1);
                                        }
                                        $orderCode = $order->order_number ?: '#' . $order->id;
                                        $waText = urlencode("Halo Kak {$order->courier_display_name}, saya pemesan di Strawberry Bakery dengan nomor pesanan {$orderCode}. Ingin menanyakan posisi/status pengantaran pesanan saya. Terima kasih!");
                                    @endphp
                                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-semibold transition-colors shadow-sm">
                                        <i class="fab fa-whatsapp text-sm"></i> Hubungi Kurir via WhatsApp
                                    </a>
                                @endif
                            </div>

                            @if($order->delivered_at)
                                <div class="mt-2 text-xs text-green-700 font-medium">
                                    <i class="fas fa-calendar-check me-1"></i> Pesanan diterima pada: {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y H:i') }} WIB
                                </div>
                            @endif
                        @else
                            <div class="mt-2 pt-2 border-t border-pink-200/60 text-xs text-amber-700 italic">
                                <i class="fas fa-info-circle me-1"></i> Kurir pengantaran belum ditugaskan. Mohon tunggu beberapa saat selagi pesanan Anda disiapkan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($order->status === 'processing')
            <div class="mb-6 bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-lg shadow-sm">
                @if($order->delivery_method === 'delivery')
                    Pesanan Anda sedang dipersiapkan dan akan segera diantar oleh kurir.
                @else
                    Pesanan siap diambil di toko.
                @endif
            </div>
        @elseif($order->status === 'completed')
            <div class="mb-6 bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded-lg shadow-sm">
                Pesanan sudah selesai. Terima kasih telah berbelanja di Strawberry Bakery!
            </div>
        @endif

        {{-- Pickup Deadline Notice --}}
        @if($order->delivery_method === 'pickup' && $order->pickup_deadline_at)
        @php
            $deadlinePassed = now()->gt($order->pickup_deadline_at);
            $boxClass = $deadlinePassed ? 'border-red-400 bg-red-50' : 'border-orange-300 bg-orange-50';
            $textClass = $deadlinePassed ? 'text-red-700' : 'text-orange-700';
        @endphp
        <div class="mb-6 rounded-xl border {{ $boxClass }} p-4">
            <p class="font-bold {{ $textClass }} mb-1">
                ⚠ {{ $deadlinePassed ? 'Batas Waktu Pengambilan Sudah Lewat!' : 'Batas Waktu Pengambilan' }}
            </p>
            <p class="text-sm {{ $textClass }}">
                Pesanan ini <strong>wajib diambil sebelum</strong>
                <strong>pukul {{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('H:i') }} WIB,
                {{ \Carbon\Carbon::parse($order->pickup_deadline_at)->format('d M Y') }}</strong>.
            </p>
        </div>
        @endif

        <h2 class="text-xl font-bold text-gray-800 mb-4">Item Pesanan</h2>
        @if($order->packages && $order->packages->isNotEmpty())
            <div class="mb-4 space-y-4">
                <span class="inline-block bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold mb-2">
                    <i class="fas fa-box-open me-1"></i> Pesanan Custom Box / Paket
                </span>
                @foreach($packageGroups as $group)
                    <div class="border border-pink-100 rounded-xl p-4 bg-pink-50/50">
                        <div class="flex justify-between items-center border-b border-pink-200 pb-2 mb-2">
                            <h3 class="font-bold text-gray-800">
                                Paket Box ({{ $group['quantity'] }}x)
                                @if($group['is_free'])
                                    <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">GRATIS (PROMO)</span>
                                @endif
                            </h3>
                            <span class="font-bold text-pink-600">
                                @if($group['is_free'])
                                    GRATIS
                                @else
                                    Rp {{ number_format($group['unit_price'], 0, ',', '.') }} x {{ $group['quantity'] }} = Rp {{ number_format($group['total_price'], 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="space-y-1">
                            @foreach($group['items'] as $pItem)
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ $pItem['quantity'] }}x {{ $pItem['name'] }}</span>
                                    <span>Rp {{ number_format($pItem['unit_price'], 0, ',', '.') }} / item</span>
                                </div>
                            @endforeach
                            @if(!empty($group['beverage_type']))
                                <div class="flex justify-between text-sm text-gray-600 border-t border-pink-100 pt-1 mt-1">
                                    <span>{{ $group['beverage_type'] === 'gelas' ? 'Air Mineral Gelas' : 'Air Mineral Botol' }} × {{ $group['quantity'] }} box</span>
                                    <span>Rp {{ number_format($group['beverage_price'], 0, ',', '.') }} / box</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="mt-5 border-t border-gray-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal paket</span>
                        <span>Rp {{ number_format($packageSubtotal, 0, ',', '.') }}</span>
                    </div>
                    @if((int) $order->discount_amount > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Diskon / box promo gratis</span>
                            <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if((int) $order->voucher_discount > 0)
                        <div class="flex justify-between text-pink-700 font-medium">
                            <span>Diskon voucher ({{ $order->voucher_code }})</span>
                            <span>- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkir @if($order->delivery_method === 'delivery' && $order->distance_km) ({{ $order->distance_km }} km) @endif</span>
                        <span>{{ (int) $order->shipping_cost > 0 ? 'Rp '.number_format($order->shipping_cost, 0, ',', '.') : 'Gratis' }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-800">
                        <span>Total dibayar</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->product->name ?? 'Produk' }}</p>
                            <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }}</p>
                            @if($order->status === 'completed' && isset($item->product->slug))
                                <a href="{{ route('products.show', $item->product->slug) }}#ulasan" class="inline-block mt-2 text-xs font-semibold px-3 py-1 bg-pink-100 text-pink-600 rounded-full hover:bg-pink-200 transition">
                                    <i class="fas fa-star text-yellow-400 me-1"></i> Beri Ulasan
                                </a>
                            @endif
                        </div>
                        <span class="font-semibold text-gray-800">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
                <div class="mt-5 border-t border-gray-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal produk</span>
                        <span>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    @if((int) $order->discount_amount > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Diskon promo</span>
                            <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if((int) $order->voucher_discount > 0)
                        <div class="flex justify-between text-pink-700 font-medium">
                            <span>Diskon voucher ({{ $order->voucher_code }})</span>
                            <span>- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkir @if($order->delivery_method === 'delivery' && $order->distance_km) ({{ $order->distance_km }} km) @endif</span>
                        <span>{{ (int) $order->shipping_cost > 0 ? 'Rp '.number_format($order->shipping_cost, 0, ',', '.') : 'Gratis' }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-800">
                        <span>Total dibayar</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if($order->payment_method)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Metode Pembayaran</h3>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200 text-sm text-gray-700">
                    {{ strtoupper($order->payment_method) }}
                </div>
            </div>
        @endif

        @if(in_array($order->payment_status, ['paid', 'success'], true))
            <div class="mt-6">
                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary w-full rounded-pill py-3 font-bold text-lg text-center block bg-pink-50 border border-pink-300 text-pink-600 hover:bg-pink-100 transition">
                    <i class="fas fa-receipt me-2"></i> Lihat / Unduh Struk
                </a>
            </div>
        @endif

        @if($order->payment_status === 'pending' && $order->snap_token)
            <div class="mt-6">
                <button id="pay-button" class="w-full rounded-pill py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold text-lg transition">
                    Bayar Sekarang dengan Midtrans
                </button>
            </div>
        @endif

        @if($order->payment_proof_path)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Bukti Pembayaran</h3>
                <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="text-pink-500 hover:underline">
                    Lihat Bukti Pembayaran
                </a>
            </div>
        @endif
    </div>
</div>

@if($order->payment_status === 'pending' && $order->snap_token)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result){
                window.location.href = '{{ route("checkout.status", $order->id) }}';
            },
            onPending: function(result){
                window.location.href = '{{ route("checkout.status", $order->id) }}';
            },
            onError: function(result){
                window.location.href = '{{ route("checkout.status", $order->id) }}';
            },
            onClose: function(){
                // Optional
            }
        });
    };
</script>
@endif

@endsection
