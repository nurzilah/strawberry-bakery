@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-8 text-center border border-gray-100">
        
        @if($order->payment_status === 'paid')
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check fa-3x"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-6">Terima kasih, pesanan Anda (ID: #{{ $order->id }}) telah kami terima dan sedang diproses.</p>
        @elseif($order->payment_status === 'pending')
            <div class="w-20 h-20 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock fa-3x"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Menunggu Pembayaran</h1>
            <p class="text-gray-600 mb-6">Pesanan Anda (ID: #{{ $order->id }}) sedang menunggu proses pembayaran.</p>
        @else
            <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times fa-3x"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Gagal/Dibatalkan</h1>
            <p class="text-gray-600 mb-6">Pesanan Anda (ID: #{{ $order->id }}) tidak dapat diproses karena pembayaran gagal atau kadaluarsa.</p>
        @endif

        <div class="bg-pink-50 rounded-xl p-6 mb-8 text-left border border-pink-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-pink-200 pb-2">Ringkasan Pesanan</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Tagihan</span>
                    <span class="font-bold text-pink-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pengambilan</span>
                    <span class="font-semibold text-gray-800">{{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-' }}</span>
                </div>
                @if($order->payment_type)
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold text-gray-800">{{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}</span>
                </div>
                @endif
                @if($order->midtrans_transaction_id)
                <div class="flex justify-between">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-semibold text-gray-800">{{ $order->midtrans_transaction_id }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($order->payment_status === 'pending' && $order->snap_token)
                <button id="pay-button" class="btn btn-strawberry rounded-pill py-2 px-6 font-semibold">
                    Bayar Sekarang
                </button>
            @endif
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary rounded-pill py-2 px-6 font-semibold">
                Lihat Detail Pesanan
            </a>
            @if(in_array($order->payment_status, ['paid', 'success'], true))
                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary rounded-pill py-2 px-6 font-semibold">
                    Unduh Struk
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill py-2 px-6 font-semibold border">
                Kembali Belanja
            </a>
        </div>
    </div>
</div>

@if($order->payment_status === 'pending' && $order->snap_token)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result){
                window.location.reload();
            },
            onPending: function(result){
                window.location.reload();
            },
            onError: function(result){
                window.location.reload();
            },
            onClose: function(){
                window.location.reload();
            }
        });
    };
</script>
@endif

@endsection
