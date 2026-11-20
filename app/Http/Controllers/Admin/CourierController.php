<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    /**
     * Display a listing of couriers.
     */
    public function index()
    {
        $couriers = User::where('role', 'kurir')->latest()->paginate(15);

        return view('admin.kurir.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new courier.
     */
    public function create()
    {
        return view('admin.kurir.create');
    }

    /**
     * Store a newly created courier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'kurir',
            'is_verified' => true,
        ]);

        return redirect()->route('admin.kurir.index')->with('success', 'Akun kurir berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified courier.
     */
    public function edit(User $kurir)
    {
        if ($kurir->role !== 'kurir') {
            abort(404);
        }

        return view('admin.kurir.edit', ['courier' => $kurir]);
    }

    /**
     * Update the specified courier in storage.
     */
    public function update(Request $request, User $kurir)
    {
        if ($kurir->role !== 'kurir') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($kurir->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($kurir->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $kurir->update($data);

        return redirect()->route('admin.kurir.index')->with('success', 'Data kurir berhasil diperbarui.');
    }

    /**
     * Remove the specified courier from storage.
     */
    public function destroy(User $kurir)
    {
        if ($kurir->role !== 'kurir') {
            abort(404);
        }

        $kurir->delete();

        return redirect()->route('admin.kurir.index')->with('success', 'Akun kurir berhasil dihapus.');
    }
}
