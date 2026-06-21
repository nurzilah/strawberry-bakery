@extends('layouts.admin')

@section('title','Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Produk</h1>
        <a href="{{ route('admin.products.create') }}"
           class="bg-pink-500 text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-pink-600">
            + Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b bg-pink-50">
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Foto</th>
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Harga</th>
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Stok</th>
                    <th class="py-3 px-2 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b hover:bg-pink-50/40 odd:bg-white even:bg-gray-50">
                        <td class="py-2 px-2">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="Foto"
                                     class="h-10 w-10 rounded object-cover">
                            @else
                                <div class="h-10 w-10 rounded bg-gray-100 text-gray-400 flex items-center justify-center text-xs">
                                    -
                                </div>
                            @endif
                        </td>
                        <td class="py-2 px-2 text-gray-800 font-medium">
                            {{ $product->name }}
                        </td>
                        <td class="py-2 px-2 text-gray-700">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="py-2 px-2 text-pink-600 font-semibold">
                            Rp {{ number_format($product->price,0,',','.') }}
                        </td>
                        <td class="py-2 px-2 text-gray-800">
                            {{ $product->stock }}
                        </td>
                        <td class="py-2 px-2 space-x-2">
                            <a href="{{ route('admin.products.edit',$product) }}"
                               class="inline-block text-xs px-3 py-1 bg-blue-500 text-black rounded hover:bg-blue-600">
                                Edit
                            </a>
                            <form action="{{ route('admin.products.destroy',$product) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">
                            Belum ada produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
