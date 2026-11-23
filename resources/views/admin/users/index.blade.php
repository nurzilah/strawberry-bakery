@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Manajemen Pengguna</h1>
        <p class="text-muted mb-0">Kelola daftar akun, hak akses role, dan pembagian wewenang sistem.</p>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">Nama</th>
                        <th class="py-3">Email & No. Telepon</th>
                        <th class="py-3">Role Saat Ini</th>
                        <th class="pe-4 py-3 text-end">Ubah Role & Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $userRole = strtolower($user->role ?? 'customer');
                        @endphp
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                <div class="small text-muted">ID: #{{ $user->id }}</div>
                            </td>
                            <td class="py-3">
                                <div>{{ $user->email }}</div>
                                <div class="small text-muted">{{ $user->phone ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                @if($userRole === 'owner')
                                    <span class="badge bg-danger rounded-pill px-3 py-1">Owner</span>
                                @elseif($userRole === 'admin')
                                    <span class="badge bg-primary rounded-pill px-3 py-1">IT Developer</span>
                                @elseif($userRole === 'kasir')
                                    <span class="badge bg-success rounded-pill px-3 py-1">Kasir</span>
                                @elseif($userRole === 'kurir')
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-1">Kurir</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1">Customer</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    {{-- IT Developer dilarang mengubah role akun Owner --}}
                                    @if(auth()->user()->role === 'admin' && $userRole === 'owner')
                                        <span class="badge bg-light text-danger border px-2 py-1">
                                            <i class="fas fa-lock me-1"></i> Dilindungi (Owner)
                                        </span>
                                    @else
                                        <form action="{{ route('owner.users.role', $user) }}" method="POST" class="d-flex align-items-center gap-2 m-0">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="form-select form-select-sm rounded-pill shadow-sm" style="min-width: 140px;">
                                                @php
                                                    $roleMap = [
                                                        'owner'    => 'Owner',
                                                        'admin'    => 'IT Developer',
                                                        'kasir'    => 'Kasir',
                                                        'kurir'    => 'Kurir',
                                                        'customer' => 'Customer',
                                                    ];
                                                @endphp
                                                @foreach($roles as $role)
                                                    {{-- IT Developer tidak boleh mengangkat akun menjadi Owner --}}
                                                    @if(auth()->user()->role === 'admin' && $role === 'owner')
                                                        @continue
                                                    @endif
                                                    <option value="{{ $role }}" {{ $userRole === $role ? 'selected' : '' }}>
                                                        {{ $roleMap[$role] ?? ucfirst($role) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3">
                                                Simpan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus Pengguna: dilarang hapus akun sendiri, dan IT Developer dilarang hapus Owner --}}
                                    @if(auth()->id() !== $user->id)
                                        @if(auth()->user()->role === 'owner' || (auth()->user()->role === 'admin' && $userRole !== 'owner'))
                                            <form action="{{ route('owner.users.destroy', $user) }}" method="POST" class="m-0 form-confirm" data-confirm-message="Apakah Anda yakin ingin menghapus akun pengguna '{{ $user->name }}'?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Pengguna">
                                                    <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada pengguna terdaftar.</td>
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
