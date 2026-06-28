@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan</h1>
        <a href="{{ route('orders.index') }}" class="text-pink-500 hover:underline">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-gray-600">Tanggal Pesanan</p>
                <p class="font-semibold text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Tanggal Pengambilan</p>
                <p class="font-semibold text-gray-800">
                    {{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-' }}
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
                <p class="text-gray-600">Total</p>
                <p class="font-semibold text-pink-500 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($order->status === 'processing')
            <div class="mb-6 bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-lg shadow-sm">
                Pesanan siap diambil.
            </div>
        @elseif($order->status === 'completed')
            <div class="mb-6 bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded-lg shadow-sm">
                Pesanan sudah selesai. Terima kasih.
            </div>
        @endif

        <h2 class="text-xl font-bold text-gray-800 mb-4">Item Pesanan</h2>
        <div class="space-y-3">
            @foreach($order->items as $item)
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->product->name ?? 'Produk' }}</p>
                        <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }}</p>
                    </div>
                    <span class="font-semibold text-gray-800">
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        @if($order->payment_method)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Metode Pembayaran</h3>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200 text-sm text-gray-700">
                    {{ strtoupper($order->payment_method) }}
                </div>
            </div>
        @endif

        @if($order->payment_status === 'pending' && $order->snap_token)
            <div class="mt-6">
                <button id="pay-button" class="btn btn-strawberry w-full rounded-pill py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold text-lg">
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
                window.location.href = '{{ route("checkout.success") }}';
            },
            onPending: function(result){
                window.location.href = '{{ route("checkout.pending") }}';
            },
            onError: function(result){
                window.location.href = '{{ route("checkout.failed") }}';
            },
            onClose: function(){
                // Optional: Nothing needs to happen here. The order is already in the database.
            }
        });
    };
</script>
@endif

@endsection
