<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification
{
    use Queueable;

    public function __construct(private Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Dikonfirmasi - Strawberry Bakery')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan'))
            ->line('Pembayaran untuk pesanan Anda sudah dikonfirmasi oleh admin.')
            ->line('Total: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Pesanan', route('orders.show', $this->order))
            ->line('Terima kasih telah berbelanja di Strawberry Bakery.');
    }
}
