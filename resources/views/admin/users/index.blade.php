@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Pengguna</h1>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600">
                        <th class="py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t">
                            <td class="py-3 text-gray-700">{{ $user->name }}</td>
                            <td class="py-3 text-gray-700">{{ $user->email }}</td>
                            <td class="py-3 text-gray-700">{{ ucfirst($user->role ?? 'customer') }}</td>
                            <td class="py-3">
                                @if(auth()->user()->role === 'admin' && $user->role === 'owner')
                                    <span class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold border border-red-200">
                                        🔒 Tidak dapat diubah
                                    </span>
                                @else
                                    <form action="{{ route('owner.users.role', $user) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="border border-gray-300 text-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                                            @foreach($roles as $role)
                                                @if(auth()->user()->role === 'admin' && $role === 'owner')
                                                    @continue
                                                @endif
                                                <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="whitespace-nowrap px-4 py-2 bg-black text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Simpan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
