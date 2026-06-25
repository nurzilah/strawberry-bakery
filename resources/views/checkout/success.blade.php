@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-600 mb-8">Terima kasih, pembayaran Anda telah kami terima. Pesanan Anda akan segera diproses.</p>
        <div class="space-y-3">
            <a href="{{ route('orders.index') }}" class="block w-full py-3 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-medium transition-colors">
                Lihat Pesanan Saya
            </a>
            <a href="{{ route('home') }}" class="block w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
