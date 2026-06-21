@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-pink-50 py-10">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="text-sm text-pink-500 hover:underline">← Kembali ke katalog</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white rounded-2xl shadow p-6">
            <div>
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600' }}"
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-cover rounded-xl">
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name ?? 'Kategori' }}</p>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                <p class="text-2xl font-bold text-pink-500 mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $product->description ?: 'Produk ini dibuat dengan bahan premium, segar, dan cocok untuk menemani momen spesial Anda.' }}
                </p>

                <div class="flex items-center space-x-3 mb-6 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>
                        Pickup: {{ $product->pickup_date ? \Carbon\Carbon::parse($product->pickup_date)->format('d F Y') : 'Minimal H-2' }}
                    </span>
                </div>

                @auth
                <form action="{{ route('cart.store') }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Tambah ke Keranjang
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-lg font-semibold hover:shadow-lg transition">
                    Login untuk beli
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
