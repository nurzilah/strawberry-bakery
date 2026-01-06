<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan profil (read-only)
    public function show()
    {
        return view('profile.show');
    }

    // Menampilkan form edit
    public function edit()
    {
        return view('profile.edit');
    }

    // Update profil
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Auth::user()->update($request->only('name', 'phone', 'address'));

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
