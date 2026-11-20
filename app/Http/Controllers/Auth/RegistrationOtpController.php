<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\RegistrationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class RegistrationOtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.verify-registration-otp', [
            'pending' => $request->session()->get('pending_registration'),
            'resendAvailableAt' => optional(OtpVerification::where('temp_identifier', $request->session()->get('pending_registration.identifier'))
                ->where('is_used', false)->latest()->first())->created_at?->addSeconds(60),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $pending = $request->session()->get('pending_registration');

        if (! $pending) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi registrasi telah berakhir. Silakan daftar kembali.']);
        }

        $otp = OtpVerification::where('temp_identifier', $pending['identifier'])
            ->where('is_used', false)->latest()->first();

        if (! $otp || $otp->expired_at->isPast()) {
            return back()->withErrors(['code' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.']);
        }

        if (! Hash::check($request->code, $otp->code)) {
            return back()->withErrors(['code' => 'Kode OTP tidak tepat.']);
        }

        // Check again because another registration may have completed while OTP was entered.
        $emailExists = User::where('email', $pending['email'])->exists();
        $phoneExists = User::where('phone', $pending['phone'])->exists();
        if ($emailExists || $phoneExists) {
            $request->session()->forget('pending_registration');
            $field = $emailExists ? 'email' : 'phone';
            $message = $emailExists
                ? 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.'
                : 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.';

            return redirect()->route('register')->withErrors([$field => $message]);
        }

        try {
            $user = User::create([
                'name' => $pending['name'],
                'email' => $pending['email'],
                'phone' => $pending['phone'],
                'password' => $pending['password'],
                'role' => 'customer',
                'is_verified' => true,
                // A registration OTP (email or WhatsApp) has validated ownership of the selected contact method.
                'email_verified_at' => $pending['method'] === 'email' ? now() : null,
            ]);
        } catch (QueryException $exception) {
            // Unique indexes also protect against simultaneous registration attempts.
            $emailExists = User::where('email', $pending['email'])->exists();
            $phoneExists = User::where('phone', $pending['phone'])->exists();
            if (! $emailExists && ! $phoneExists) {
                throw $exception;
            }

            $request->session()->forget('pending_registration');
            $field = $emailExists ? 'email' : 'phone';
            $message = $emailExists
                ? 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.'
                : 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.';

            return redirect()->route('register')->withErrors([$field => $message]);
        }

        $otp->update(['is_used' => true, 'user_id' => $user->id]);
        OtpVerification::where('temp_identifier', $pending['identifier'])->where('id', '!=', $otp->id)->update(['is_used' => true]);
        $request->session()->forget('pending_registration');
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Registrasi berhasil. Akun Anda telah aktif.');
    }

    public function resend(Request $request, RegistrationOtpService $otpService): RedirectResponse
    {
        $pending = $request->session()->get('pending_registration');
        if (! $pending) {
            return redirect()->route('register');
        }

        $lastOtp = OtpVerification::where('temp_identifier', $pending['identifier'])->where('is_used', false)->latest()->first();
        if ($lastOtp && $lastOtp->created_at->addSeconds(60)->isFuture()) {
            return back()->withErrors(['code' => 'Tunggu 60 detik sejak pengiriman OTP terakhir sebelum mengirim ulang.']);
        }

        OtpVerification::where('temp_identifier', $pending['identifier'])->where('is_used', false)->update(['is_used' => true]);

        try {
            $otpService->createAndSend($pending['identifier'], $pending['method'], $pending['destination']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['code' => 'OTP gagal dikirim. Periksa konfigurasi layanan lalu coba lagi.']);
        }

        return back()->with('status', 'Kode OTP baru sudah dikirim.');
    }
}
