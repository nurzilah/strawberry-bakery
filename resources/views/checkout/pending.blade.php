@extends('layouts.app')

@section('title', 'Menunggu Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Menunggu Pembayaran</h1>
        <p class="text-gray-600 mb-8">Silakan selesaikan pembayaran Anda sesuai instruksi. Pesanan Anda akan diproses setelah pembayaran diterima.</p>
        <div class="space-y-3">
            <a href="{{ route('orders.index') }}" class="block w-full py-3 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-medium transition-colors">
                Lihat Status Pesanan
            </a>
        </div>
    </div>
</div>
@endsection
