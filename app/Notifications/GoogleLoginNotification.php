<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GoogleLoginNotification extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Login Google Berhasil - Strawberry Bakery')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan'))
            ->line('Anda berhasil login menggunakan Google.')
            ->line('Jika ini bukan Anda, segera ganti password akun Anda.');
    }
}
