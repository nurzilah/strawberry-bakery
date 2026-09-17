<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $this->order->loadMissing(['user', 'items']);

        $mail = (new MailMessage)
            ->subject('Pembayaran Dikonfirmasi - Strawberry Bakery')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan'))
            ->line('Pembayaran pesanan Anda sudah kami terima. Struk pembayaran terlampir pada email ini.')
            ->line('Total: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Pesanan', route('orders.show', $this->order))
            ->line('Terima kasih telah berbelanja di Strawberry Bakery.');

        return $mail->attachData(
            Pdf::loadView('admin.orders.receipt_pdf', ['order' => $this->order])->output(),
            'Struk_Pesanan_' . $this->order->id . '.pdf',
            ['mime' => 'application/pdf']
        );
    }
}
