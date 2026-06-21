@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $title }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-pink-500 hover:underline">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <form action="{{ $route }}" method="POST" class="space-y-5">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon (opsional)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Aktif</span>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
