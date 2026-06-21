@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">{{ $title }}</h1>

    <form action="{{ $route }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif

        <div>
            <label class="block text-sm font-medium mb-1">Nama Produk</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2"
                   value="{{ old('name',$product->name) }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Slug</label>
            <input type="text" name="slug" class="w-full border rounded px-3 py-2"
                   value="{{ old('slug',$product->slug) }}" placeholder="Otomatis dari nama jika kosong">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id',$product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Harga</label>
            <input type="number" name="price" class="w-full border rounded px-3 py-2"
                   value="{{ old('price',$product->price) }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Stok</label>
            <input type="number" name="stock" class="w-full border rounded px-3 py-2"
                   value="{{ old('stock',$product->stock) }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('description',$product->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Foto Produk</label>
            <input type="file" name="image" accept="image/*,.heic,.heif" class="w-full border rounded px-3 py-2" {{ $method === 'POST' ? 'required' : '' }}>
            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP, HEIC/HEIF. Maksimal 5MB.</p>
            <div class="mt-3 rounded-lg border border-pink-100 bg-pink-50/60 p-3">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-800">
                    <input type="checkbox" name="ai_enhance" value="1" {{ old('ai_enhance') ? 'checked' : '' }}>
                    Tingkatkan foto dengan AI
                </label>
                <p class="mt-1 text-xs text-gray-600">
                    AI akan membuat foto produk lebih rapi, terang, dan menarik untuk katalog.
                </p>
                <textarea name="ai_prompt" rows="2" class="mt-2 w-full border rounded px-3 py-2 text-sm"
                          placeholder="Opsional: contoh, latar putih premium, nuansa strawberry, lighting natural">{{ old('ai_prompt') }}</textarea>
            </div>
            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$product->image) }}" alt="Preview" class="h-24 w-24 object-cover rounded">
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_available" value="1"
                    {{ old('is_available',$product->is_available ?? true) ? 'checked' : '' }}>
                Tersedia
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1"
                    {{ old('is_featured',$product->is_featured) ? 'checked' : '' }}>
                Produk Unggulan
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_new" value="1"
                    {{ old('is_new',$product->is_new) ? 'checked' : '' }}>
                Produk Baru
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_promo" value="1"
                    {{ old('is_promo',$product->is_promo) ? 'checked' : '' }}>
                Promo
            </label>
        </div>

        <div class="pt-4">
            <button class="bg-pink-500 text-black px-6 py-2 rounded-lg font-semibold">
                Simpan
            </button>
            <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 text-sm">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
