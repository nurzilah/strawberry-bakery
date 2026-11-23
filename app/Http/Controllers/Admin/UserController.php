<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        $roles = ['owner', 'admin', 'kasir', 'kurir', 'customer'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:owner,admin,kasir,kurir,customer',
        ]);

        $currentUserRole = strtolower(auth()->user()->role ?? '');
        $targetUserRole  = strtolower($user->role ?? '');
        $requestedRole   = strtolower($request->role);

        // IT Developer (admin) dilarang mengubah akun Owner atau mengangkat user lain menjadi Owner
        if ($currentUserRole === 'admin') {
            if ($targetUserRole === 'owner') {
                return redirect()->back()->with('error', 'IT Developer tidak memiliki izin mengubah role akun Owner.');
            }
            if ($requestedRole === 'owner') {
                return redirect()->back()->with('error', 'IT Developer tidak diizinkan mengangkat pengguna menjadi Owner.');
            }
        }

        $user->update(['role' => $requestedRole]);

        $roleLabels = [
            'owner' => 'Owner',
            'admin' => 'IT Developer',
            'kasir' => 'Kasir',
            'kurir' => 'Kurir',
            'customer' => 'Customer',
        ];

        return redirect()->back()->with('success', "Role pengguna {$user->name} berhasil diubah menjadi " . ($roleLabels[$requestedRole] ?? $requestedRole) . '.');
    }

    public function destroy(User $user)
    {
        $currentUserRole = strtolower(auth()->user()->role ?? '');
        $targetUserRole  = strtolower($user->role ?? '');

        // Tidak boleh menghapus akun sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // IT Developer (admin) dilarang menghapus akun Owner
        if ($currentUserRole === 'admin' && $targetUserRole === 'owner') {
            return redirect()->back()->with('error', 'IT Developer tidak memiliki izin untuk menghapus akun Owner.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Akun pengguna {$userName} berhasil dihapus.");
    }
}
