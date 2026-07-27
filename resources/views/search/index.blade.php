@extends('layouts.app')

@section('title', 'Pencarian')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="flex items-center space-x-3 mb-8">
        <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h1 class="text-3xl font-bold text-gray-800">Hasil Pencarian</h1>
    </div>

    <form action="{{ route('search.global') }}" method="GET" class="mb-8">
        <div class="flex items-center space-x-3">
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Cari produk, kategori, halaman..."
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition">
                Cari
            </button>
        </div>
    </form>

    @if($query === '')
        <div class="bg-white rounded-xl p-6 text-gray-600">
            Masukkan kata kunci untuk mulai mencari.
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Produk</h2>
                @forelse($products as $product)
                    <div class="flex items-center justify-between border-b border-gray-100 py-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Kategori' }}</p>
                        </div>
                        @if(!empty($product->slug))
                            <a href="{{ route('products.show', $product->slug) }}" class="text-pink-500 hover:underline text-sm">
                                Lihat
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Produk tidak ditemukan.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Kategori</h2>
                @forelse($categories as $category)
                    <div class="flex items-center justify-between border-b border-gray-100 py-3">
                        <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-pink-500 hover:underline text-sm">
                            Lihat
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500">Kategori tidak ditemukan.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Halaman</h2>
                @forelse($pages as $page)
                    <div class="flex items-center justify-between border-b border-gray-100 py-3">
                        <p class="font-semibold text-gray-800">{{ $page['title'] }}</p>
                        <a href="{{ $page['route'] }}" class="text-pink-500 hover:underline text-sm">Buka</a>
                    </div>
                @empty
                    <p class="text-gray-500">Halaman tidak ditemukan.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection
