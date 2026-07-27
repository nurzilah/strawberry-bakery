@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Gagal</h1>
        <p class="text-gray-600 mb-8">Maaf, terjadi kesalahan atau pembayaran Anda dibatalkan. Silakan coba lagi.</p>
        <div class="space-y-3">
            <a href="{{ route('checkout.index') }}" class="block w-full py-3 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-medium transition-colors">
                Coba Bayar Lagi
            </a>
            <a href="{{ route('home') }}" class="block w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
