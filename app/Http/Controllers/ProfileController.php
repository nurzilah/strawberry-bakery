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
            'delivery_preference' => 'required|in:pickup,delivery',
            'phone' => 'required_if:delivery_preference,delivery|nullable|string|max:20',
            'address' => 'required_if:delivery_preference,delivery|nullable|string',
            'city' => 'required_if:delivery_preference,delivery|nullable|string',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|numeric|digits_between:1,5',
            'address_note' => 'nullable|string',
        ], [
            'phone.required_if' => 'No. Handphone wajib diisi jika preferensi adalah Diantar.',
            'address.required_if' => 'Alamat Lengkap wajib diisi jika preferensi adalah Diantar.',
            'city.required_if' => 'Kota/Kabupaten wajib diisi jika preferensi adalah Diantar.',
        ]);

        Auth::user()->update($request->only('name', 'phone', 'address', 'city', 'district', 'postal_code', 'address_note', 'delivery_preference'));

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
