<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PackageReceiptGrouper;

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
        $this->order->loadMissing(['user', 'items', 'packages.items.product']);

        $mail = (new MailMessage)
            ->subject('Pembayaran Dikonfirmasi - Strawberry Bakery')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan'))
            ->line('Pembayaran pesanan Anda sudah kami terima. Struk pembayaran terlampir pada email ini.')
            ->line('Total: Rp ' . number_format($this->order->total_amount, 0, ',', '.'));

        $packageGroups = app(PackageReceiptGrouper::class)->group($this->order->packages);
        if (! empty($packageGroups)) {
            $mail->line('Rincian custom box:');
            foreach ($packageGroups as $group) {
                $mail->line(($group['is_free'] ? 'BOX GRATIS PROMO' : 'BOX CUSTOM') . ' x ' . $group['quantity'] . ' box');

                foreach ($group['items'] as $item) {
                    $mail->line('- ' . $item['quantity'] . ' x ' . $item['name'] . ' (Rp ' . number_format($item['unit_price'], 0, ',', '.') . ')');
                }

                if (! empty($group['beverage_type'])) {
                    $beverageName = $group['beverage_type'] === 'gelas' ? 'Air Mineral Gelas' : 'Air Mineral Botol';
                    $mail->line('- ' . $beverageName . ' x ' . $group['quantity'] . ' box (Rp ' . number_format($group['beverage_price'], 0, ',', '.') . ' / box)');
                }

                if ($group['is_free']) {
                    $mail->line('Promo: GRATIS x ' . $group['quantity'] . ' box');
                } else {
                    $mail->line('Harga per box: Rp ' . number_format($group['unit_price'], 0, ',', '.'));
                    $mail->line('Subtotal ' . $group['quantity'] . ' box: Rp ' . number_format($group['total_price'], 0, ',', '.'));
                }
            }
        }

        if (filled($this->order->bonus_item_name) && (int) $this->order->bonus_item_qty > 0) {
            $mail->line('Bonus: ' . $this->order->bonus_item_qty . ' ' . $this->order->bonus_item_name);
        }

        $mail->action('Lihat Pesanan', route('orders.show', $this->order))
            ->line('Terima kasih telah berbelanja di Strawberry Bakery.');

        return $mail->attachData(
            Pdf::loadView('admin.orders.receipt_pdf', ['order' => $this->order, 'packageGroups' => $packageGroups])->output(),
            'Struk_Pesanan_' . $this->order->id . '.pdf',
            ['mime' => 'application/pdf']
        );
    }
}
