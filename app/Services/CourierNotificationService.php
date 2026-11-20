<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierNotificationService
{
    /**
     * Send WhatsApp notification to customer when delivery status changes to 'dalam_pengantaran' or 'terkirim'.
     */
    public function sendStatusUpdateNotification(Order $order, string $newStatus): void
    {
        $order->loadMissing(['user', 'courier']);
        $phone = $order->user?->phone;
        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url');

        if (!filled($phone) || !filled($token) || !filled($url)) {
            return;
        }

        $customerName = $order->user->name ?? 'Pelanggan';
        $orderNum = $order->order_number ?: '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
        $message = '';

        $courierName = $order->courier?->name ?? $order->courier_name ?? 'Kurir Toko';
        $courierPhone = $order->courier?->phone ?? $order->courier_phone;
        $phoneInfo = $courierPhone ? " (No. HP/WA: {$courierPhone})" : '';

        if (in_array($newStatus, ['dalam_pengantaran', 'dalam_perjalanan'], true)) {
            $message = "Halo {$customerName}, pesanan {$orderNum} saat ini sedang dalam pengantaran menuju lokasi Anda oleh {$courierName}{$phoneInfo}. Mohon pastikan penerima berada di alamat pengantaran. Terima kasih!";
        } elseif ($newStatus === 'terkirim') {
            $message = "Halo {$customerName}, pesanan {$orderNum} telah berhasil diserahkan oleh kurir. Terima kasih telah berbelanja di Strawberry Bakery!";
        } else {
            return;
        }

        try {
            $formattedPhone = $this->toFonnteNumber($phone);
            $response = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->post($url, [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);

            if ($response->failed() || $response->json('status') === false) {
                Log::warning('WA Notifikasi Kurir ke Pelanggan gagal dikirim.', [
                    'order_id' => $order->id,
                    'status' => $newStatus,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Exception WA Notifikasi Kurir ke Pelanggan:', [
                'order_id' => $order->id,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Send WhatsApp notification to assigned courier with delivery details & Google Maps navigation link.
     */
    public function sendAssignmentNotificationToCourier(Order $order, $courier = null): void
    {
        $courier = $courier ?? $order->courier;
        $courierPhone = $courier?->phone;
        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url');

        if (!filled($courierPhone) || !filled($token) || !filled($url)) {
            return;
        }

        $order->loadMissing('user');
        $courierName = $courier->name ?? 'Kurir';
        $orderNum = $order->order_number ?: '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
        $customerName = $order->user->name ?? 'Pelanggan';
        $customerPhone = $order->user->phone ?? '-';
        $address = $order->delivery_address ?? '-';
        $dist = $order->distance_km ? "{$order->distance_km} km" : '-';

        // Google Maps Link
        if ($order->delivery_lat && $order->delivery_lng) {
            $mapsLink = "https://www.google.com/maps?q={$order->delivery_lat},{$order->delivery_lng}";
        } else {
            $mapsLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($address);
        }

        $message = "Halo {$courierName},\n\nAnda mendapatkan penugasan pengantaran pesanan {$orderNum} dari Strawberry Bakery.\n\n" .
                   "Detail Pengantaran:\n" .
                   "- Penerima: {$customerName}\n" .
                   "- No. HP Pelanggan: {$customerPhone}\n" .
                   "- Alamat Tujuan: {$address}\n" .
                   "- Estimasi Jarak: {$dist}\n" .
                   "- Rute Maps: {$mapsLink}\n\n" .
                   "Silakan ambil pesanan di toko dan antarkan ke pelanggan dengan selamat. Semangat bertugas!";

        try {
            $formattedPhone = $this->toFonnteNumber($courierPhone);
            $response = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->post($url, [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);

            if ($response->failed() || $response->json('status') === false) {
                Log::warning('WA Notifikasi Penugasan ke Kurir gagal dikirim.', [
                    'order_id' => $order->id,
                    'courier_phone' => $courierPhone,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Exception WA Notifikasi Penugasan ke Kurir:', [
                'order_id' => $order->id,
                'exception' => $exception->getMessage(),
            ]);
        }
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
