<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class RegistrationOtpService
{
    public function createAndSend(string $identifier, string $method, string $destination): OtpVerification
    {
        $code = (string) random_int(100000, 999999);

        $otp = OtpVerification::create([
            'temp_identifier' => $identifier,
            'code' => Hash::make($code),
            'method' => $method,
            'destination' => $destination,
            'expired_at' => now()->addMinutes((int) config('services.fonnte.otp_expiry_minutes', 5)),
        ]);

        try {
            $this->send($method, $destination, $code);
        } catch (\Throwable $exception) {
            $otp->delete();
            throw $exception;
        }

        return $otp;
    }

    private function send(string $method, string $destination, string $code): void
    {
        $expiryMinutes = (int) config('services.fonnte.otp_expiry_minutes', 5);
        $message = "Kode OTP Strawberry Bakery Anda: {$code}. Kode berlaku selama {$expiryMinutes} menit. Jangan bagikan kode ini kepada siapa pun.";

        if ($method === 'email') {
            Mail::raw($message, function ($mail) use ($destination) {
                $mail->to($destination)->subject('Kode OTP Registrasi Strawberry Bakery');
            });

            return;
        }

        $token = config('services.fonnte.token');
        if (blank($token)) {
            throw new RuntimeException('Konfigurasi Fonnte belum tersedia.');
        }

        $response = Http::withHeaders(['Authorization' => $token])
            ->asForm()
            ->post(config('services.fonnte.url'), [
                'target' => $this->toFonnteNumber($destination),
                'message' => $message,
            ]);

        if ($response->failed() || $response->json('status') === false) {
            throw new RuntimeException('Fonnte gagal mengirim OTP.');
        }
    }

    private function toFonnteNumber(string $phone): string
    {
        return '62'.substr($phone, 1);
    }
}
