<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Notifications\GoogleLoginNotification;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google tidak memberikan email. Silakan coba lagi.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User Google',
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
            ]);
        }

        if (Schema::hasColumn('users', 'google_id')) {
            $user->google_id = $googleUser->getId();
            $user->save();
        }

        Auth::login($user, true);

        $user->notify(new GoogleLoginNotification());

        return redirect()->route('home')->with('success', 'Login Google berhasil.');
    }
}
