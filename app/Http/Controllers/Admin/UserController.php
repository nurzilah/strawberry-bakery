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
        $roles = ['customer', 'admin', 'superadmin', 'kasir'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,admin,superadmin,kasir',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
