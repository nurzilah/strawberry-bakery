@extends('layouts.app')

@section('title','Rekomendasi Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        Rekomendasi Untuk Anda
    </h1>
    <p class="text-sm text-gray-600 mb-6">
        Rekomendasi dihitung menggunakan Collaborative Filtering dengan metode K-Nearest Neighbor (KNN) berdasarkan rating pengguna lain yang mirip dengan Anda.
    </p>

    @if(empty($recommendations) || count($recommendations) === 0)
        <div class="bg-yellow-50 text-yellow-800 px-4 py-3 rounded-lg text-sm">
            Belum ada rekomendasi. Silakan berikan rating pada beberapa produk terlebih dahulu.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $item)
                @php
                    $product = is_array($item) ? $item['product'] : $item;
                    $score = is_array($item) ? $item['score'] : null;
                @endphp
                <div class="bg-white rounded-2xl shadow p-4">
                    <h2 class="font-semibold text-gray-800 mb-1">
                        {{ $product->name }}
                    </h2>
                    <p class="text-sm text-gray-500 mb-2">
                        {{ $product->category->name ?? 'Tanpa kategori' }}
                    </p>
                    <p class="text-pink-600 font-bold mb-2">
                        Rp {{ number_format($product->price,0,',','.') }}
                    </p>
                    @if(!is_null($score))
                        <p class="text-xs text-gray-500 mb-3">
                            Perkiraan rating (CF + KNN): {{ number_format($score, 2) }}
                        </p>
                    @endif
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="inline-block text-sm bg-pink-500 text-white px-4 py-2 rounded-lg">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
