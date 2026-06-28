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
        $roles = ['owner', 'admin', 'kasir', 'customer'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:owner,admin,kasir,customer',
        ]);

        if (auth()->user()->role === 'admin') {
            if ($user->role === 'owner') {
                abort(403, 'Anda tidak memiliki akses untuk mengubah role ini');
            }
            if ($request->role === 'owner') {
                abort(403, 'Anda tidak memiliki akses untuk mengubah role ini');
            }
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
