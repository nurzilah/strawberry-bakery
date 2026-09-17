<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_must_verify_otp_before_an_account_is_created(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'verification_method' => 'email',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'terms' => 'on',
        ]);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertDatabaseCount('otp_verifications', 1);
        $response->assertRedirect(route('register.otp.create'));
    }

    public function test_account_is_created_after_correct_otp_is_entered(): void
    {
        Mail::fake();
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'verification_method' => 'email',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'terms' => 'on',
        ]);

        $otp = OtpVerification::firstOrFail();
        // Replace the generated hash with a known OTP; production OTPs remain random and hashed.
        $otp->update(['code' => bcrypt('123456')]);

        $response = $this->post(route('register.otp.store'), ['code' => '123456']);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'is_verified' => true]);
        $this->assertDatabaseHas('otp_verifications', ['id' => $otp->id, 'is_used' => true]);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
