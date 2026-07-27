<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class GoogleController extends Controller
{
    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            return redirect()->route('login')->withErrors([
                'email' => 'Autentikasi Google gagal. Silakan coba lagi.',
            ]);
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (!$googleId || !$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Google tidak memberikan ID atau email yang valid.',
            ]);
        }

        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleId,
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        }

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google Customer',
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $googleUser->getAvatar(),
                'role' => 'customer',
                'password' => Hash::make(Str::random(64)),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('success', 'Login Google berhasil.');
    }
}
