@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition">
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600">
                        <th class="py-3">Nama</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3">Aktif</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-t">
                            <td class="py-3 text-gray-700">{{ $category->name }}</td>
                            <td class="py-3 text-gray-700">{{ $category->slug }}</td>
                            <td class="py-3 text-gray-700">{{ $category->is_active ? 'Ya' : 'Tidak' }}</td>
                            <td class="py-3">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-pink-500 hover:underline">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
