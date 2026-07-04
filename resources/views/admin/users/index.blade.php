@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: inherit;">Manajemen Pengguna</h1>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Role</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">{{ $user->name }}</td>
                            <td class="py-3">{{ $user->email }}</td>
                            <td class="py-3">
                                @if($user->role === 'owner')
                                    <span class="badge bg-danger">{{ ucfirst($user->role) }}</span>
                                @elseif($user->role === 'admin')
                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                @elseif($user->role === 'kasir')
                                    <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->role ?? 'customer') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                @if(auth()->user()->role === 'admin' && $user->role === 'owner')
                                    <span class="badge bg-light text-danger border">🔒 Tidak dapat diubah</span>
                                @else
                                    <form action="{{ route('owner.users.role', $user) }}" method="POST" class="d-flex justify-content-end align-items-center gap-2 m-0">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="form-select form-select-sm" style="width: 130px;">
                                            @foreach($roles as $role)
                                                @if(auth()->user()->role === 'admin' && $role === 'owner')
                                                    @continue
                                                @endif
                                                <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-dark btn-sm">
                                            Simpan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-transparent py-3 border-0">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
