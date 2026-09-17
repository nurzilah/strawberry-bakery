<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShortLink;
use App\Notifications\PaymentConfirmed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ReceiptDeliveryService
{
    /**
     * Deliver a paid-order receipt once. A failed delivery is deliberately not
     * marked as sent, so it can be retried from a later payment-status sync.
     */
    public function sendOnce(Order $order): void
    {
        if ($order->receipt_sent_at) {
            return;
        }

        // Claim first so a Midtrans retry and a customer page refresh cannot
        // send the same receipt twice at the same time.
        $claimed = Order::query()
            ->whereKey($order->getKey())
            ->whereNull('receipt_sent_at')
            ->update(['receipt_sent_at' => now()]);

        if ($claimed !== 1) {
            return;
        }

        $order->refresh()->loadMissing(['user', 'items']);
        $sent = false;

        if (filled($order->user?->email)) {
            try {
                $order->user->notify(new PaymentConfirmed($order));
                $sent = true;
            } catch (\Throwable $exception) {
                Log::warning('Email struk gagal dikirim.', ['order_id' => $order->id, 'exception' => $exception->getMessage()]);
            }
        }

        if (filled($order->user?->phone) && filled(config('services.fonnte.token'))) {
            try {
                $response = Http::withHeaders(['Authorization' => config('services.fonnte.token')])
                    ->asForm()
                    ->post(config('services.fonnte.url'), [
                        'target' => $this->toFonnteNumber($order->user->phone),
                        'message' => $this->whatsappMessage($order),
                    ]);

                if ($response->failed() || $response->json('status') === false) {
                    throw new \RuntimeException('Provider WhatsApp menolak pengiriman struk.');
                }

                $sent = true;
            } catch (\Throwable $exception) {
                Log::warning('WhatsApp struk gagal dikirim.', ['order_id' => $order->id, 'exception' => $exception->getMessage()]);
            }
        }

        if (! $sent) {
            Order::whereKey($order->id)->update(['receipt_sent_at' => null]);
            Log::info('Struk belum dikirim karena kontak atau layanan pengiriman belum tersedia.', ['order_id' => $order->id]);
        }
    }

    public function receiptUrl(Order $order): string
    {
        return URL::temporarySignedRoute('orders.receipt.shared', now()->addDays(7), ['order' => $order->id]);
    }

    /**
     * WhatsApp auto-linkifies long digit sequences (like the signed URL's
     * expires/signature values), which breaks the link when tapped. A short
     * redirect link avoids that entirely.
     */
    private function shortReceiptUrl(Order $order): string
    {
        do {
            $code = Str::lower(Str::random(8));
        } while (ShortLink::where('code', $code)->exists());

        ShortLink::create([
            'code' => $code,
            'target_url' => $this->receiptUrl($order),
            'expires_at' => now()->addDays(7),
        ]);

        return route('short.redirect', ['code' => $code]);
    }

    private function whatsappMessage(Order $order): string
    {
        $invoice = $order->order_number ?: '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
        $total = number_format((int) $order->total_amount, 0, ',', '.');
        $tanggal = $order->created_at->format('d/m/Y H:i');
        $metode = strtoupper($order->payment_method ?? '-');

        $message = "*STRAWBERRY CAKE & BAKERY*\n";
        $message .= "Jl. AR. Hakim No.149, Mangkukusuman\n";
        $message .= "Kota Tegal\n";
        $message .= "WA: +62 815-7173-118\n";
        $message .= "------------------------------\n";
        $message .= "No. TRX  : {$invoice}\n";
        $message .= "Tanggal  : {$tanggal}\n";
        $message .= "Pemesan  : {$order->user->name}\n";
        $message .= "Metode   : {$metode}\n";
        $message .= "------------------------------\n";

        foreach ($order->items as $item) {
            $subtotal = number_format((int) $item->price * $item->quantity, 0, ',', '.');
            $message .= "{$item->product_name}\n";
            $message .= "{$item->quantity} x " . number_format((int) $item->price, 0, ',', '.') . "   Rp {$subtotal}\n";
        }

        $message .= "------------------------------\n";
        $message .= "*TOTAL*   Rp {$total}\n";
        $message .= "------------------------------\n\n";
        $message .= "Terima Kasih Atas Kunjungan Anda!\n";
        $message .= "Tumbuh bersama rasa manis.";

        return $message;
    }

    private function toFonnteNumber(string $phone): string
    {
        $number = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        return str_starts_with($number, '62') ? $number : '62' . $number;
    }
}
