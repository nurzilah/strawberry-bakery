<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\RegistrationOtpService;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, RegistrationOtpService $otpService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'regex:/^08[0-9]{8,13}$/'],
            'verification_method' => ['required', 'in:email,whatsapp'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'terms' => ['accepted'],
        ], [
            'password.mixed' => 'Password harus memakai huruf besar dan huruf kecil.',
            'password.numbers' => 'Password harus memakai angka.',
            'password.symbols' => 'Password harus memakai simbol.',
            'terms.accepted' => 'Syarat & Ketentuan harus dicentang sebelum mendaftar.',
            'phone.regex' => 'Nomor HP harus menggunakan format Indonesia, misalnya 081234567890.',
        ]);

        $identifier = (string) Str::uuid();
        $method = $request->string('verification_method')->toString();
        $destination = $method === 'email' ? $request->email : $request->phone;

        $request->session()->put('pending_registration', [
            'identifier' => $identifier,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'method' => $method,
            'destination' => $destination,
        ]);

        try {
            $otpService->createAndSend($identifier, $method, $destination);
        } catch (\Throwable $exception) {
            report($exception);
            $request->session()->forget('pending_registration');
            return back()->withInput()->withErrors(['verification_method' => 'OTP gagal dikirim. Periksa konfigurasi layanan lalu coba lagi.']);
        }

        return redirect()->route('register.otp.create');
    }
}
