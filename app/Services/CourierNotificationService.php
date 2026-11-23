<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierNotificationService
{
    /**
     * Build formatted WhatsApp message for customer based on delivery status.
     */
    public function buildCustomerMessage(Order $order, string $newStatus): string
    {
        $order->loadMissing(['user', 'courier']);

        $customerName = $order->user->name ?? 'Pelanggan';
        $orderNum = $order->order_number ?: '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);

        $courierName = $order->courier?->name ?? $order->courier_name ?? 'Kurir Toko';
        $courierPhone = $order->courier?->phone ?? $order->courier_phone;
        $courierPhoneText = $courierPhone ?: 'Hubungi Toko';

        $isPaid = in_array(strtolower((string) $order->payment_status), ['paid', 'success', 'settlement'], true)
            || filled($order->paid_at);
        $totalFormatted = number_format((int) $order->total_amount, 0, ',', '.');

        if (in_array($newStatus, ['dalam_pengantaran', 'dalam_perjalanan'], true)) {
            if ($isPaid) {
                $paymentInfo = "Status Pembayaran: *LUNAS* (Pesanan sudah dibayar, Anda cukup menerima barang saat kurir tiba tanpa biaya tambahan).";
            } else {
                $paymentInfo = "Status Pembayaran: *BELUM LUNAS (COD)* - Mohon siapkan uang pembayaran sebesar *Rp {$totalFormatted}* untuk diserahkan ke kurir saat pesanan tiba.";
            }

            return "Halo *{$customerName}*,\n\n" .
                   "Kabar baik! Pesanan *{$orderNum}* saat ini sedang dalam pengantaran menuju lokasi Anda.\n\n" .
                   "*Informasi Kurir & Pengantaran:*\n" .
                   "- Nama Kurir: {$courierName}\n" .
                   "- No. HP/WA Kurir: {$courierPhoneText}\n" .
                   "- {$paymentInfo}\n\n" .
                   "Mohon pastikan penerima berada di alamat tujuan dan nomor telepon dapat dihubungi saat kurir tiba. Terima kasih telah berbelanja di Strawberry Bakery!";
        }

        if ($newStatus === 'terkirim') {
            return "Halo *{$customerName}*,\n\n" .
                   "Pesanan *{$orderNum}* telah berhasil diserahkan oleh kurir (*{$courierName}*).\n\n" .
                   "Terima kasih telah berbelanja di Strawberry Bakery! Selamat menikmati hidangan kami. Kami nantikan pesanan Anda berikutnya! 😊";
        }

        return '';
    }

    /**
     * Build formatted WhatsApp message for courier with delivery details, item breakdown, payment status, and maps link.
     */
    public function buildCourierMessage(Order $order, $courier = null): string
    {
        $courier = $courier ?? $order->courier;
        $order->loadMissing(['user', 'items', 'packages.items.product']);

        $courierName = $courier->name ?? 'Kurir';
        $orderNum = $order->order_number ?: '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
        $customerName = $order->user->name ?? 'Pelanggan';
        $customerPhone = $order->user->phone ?? '-';
        $address = $order->delivery_address ?? '-';
        $dist = filled($order->distance_km) ? "{$order->distance_km} km" : 'Tidak tersedia';

        // Google Maps Link
        if ($order->delivery_lat && $order->delivery_lng) {
            $mapsLink = "https://www.google.com/maps?q={$order->delivery_lat},{$order->delivery_lng}";
        } else {
            $mapsLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($address);
        }

        // Rincian Item Pesanan
        $itemDetails = [];

        // 1. Paket Box / Custom Box (jika ada)
        if ($order->packages && $order->packages->isNotEmpty()) {
            $packageGroups = app(PackageReceiptGrouper::class)->group($order->packages);
            foreach ($packageGroups as $group) {
                $groupTitle = $group['is_free']
                    ? "Box Gratis Promo x {$group['quantity']}"
                    : "Box Custom x {$group['quantity']}";
                $itemDetails[] = "- *{$groupTitle}*:";

                foreach ($group['items'] as $pItem) {
                    $itemDetails[] = "   * {$pItem['name']} x{$pItem['quantity']}";
                }

                if (!empty($group['beverage_type'])) {
                    $bevName = $group['beverage_type'] === 'gelas' ? 'Air Mineral Gelas' : 'Air Mineral Botol';
                    $itemDetails[] = "   * {$bevName} x{$group['quantity']}";
                }
            }
        }

        // 2. Regular Item / Satuan (jika ada)
        if ($order->items && $order->items->isNotEmpty()) {
            if (!empty($itemDetails)) {
                $itemDetails[] = "- *Item Satuan / Tambahan*:";
            }
            foreach ($order->items as $item) {
                $prefix = (!empty($order->packages && $order->packages->isNotEmpty())) ? "   * " : "- ";
                $itemDetails[] = "{$prefix}{$item->product_name} x{$item->quantity}";
            }
        }

        // 3. Bonus Produk (jika ada)
        if (filled($order->bonus_item_name) && (int) $order->bonus_item_qty > 0) {
            $itemDetails[] = "- *Bonus Toko*: {$order->bonus_item_name} x{$order->bonus_item_qty}";
        }

        $itemsSummary = !empty($itemDetails) ? implode("\n", $itemDetails) : "- (Tidak ada rincian item)";

        // Status Pembayaran & Tagihan
        $isPaid = in_array(strtolower((string) $order->payment_status), ['paid', 'success', 'settlement'], true)
            || filled($order->paid_at);
        $totalFormatted = number_format((int) $order->total_amount, 0, ',', '.');

        if ($isPaid) {
            $paymentStatusText = "*LUNAS (Tidak perlu tagih pembayaran)*";
        } else {
            $paymentStatusText = "*BELUM LUNAS (COD) - Tagih sebesar Rp {$totalFormatted}*";
        }

        // Jadwal Pengantaran & Batas Keberangkatan
        $deliveryDate = $order->requested_date ? \Carbon\Carbon::parse($order->requested_date)->format('d/m/Y') : 'Hari ini';
        $targetTime = $order->formatted_target_time;
        $travelMins = $order->estimated_travel_minutes ? "± {$order->estimated_travel_minutes} menit" : 'Tidak tersedia';
        $latestDeparture = $order->latest_departure_time ? "Pukul {$order->latest_departure_time}" : 'Segera (Sesegera mungkin)';

        $scheduleSection = "*Jadwal Pengantaran:*\n" .
                           "- Tanggal Pengantaran: {$deliveryDate}\n" .
                           "- Target Tiba di Lokasi Customer: {$targetTime}\n" .
                           "- Estimasi Lama Perjalanan: {$travelMins} ({$dist})\n" .
                           "- *Wajib Berangkat dari Toko Maksimal: {$latestDeparture}*";

        return "Halo *{$courierName}*,\n\n" .
               "Anda mendapatkan penugasan pengantaran pesanan *{$orderNum}* dari Strawberry Bakery.\n\n" .
               "{$scheduleSection}\n\n" .
               "*Rincian Item Pesanan:*\n" .
               "{$itemsSummary}\n\n" .
               "*Status Pembayaran & Tagihan:*\n" .
               "{$paymentStatusText}\n\n" .
               "*Detail Tujuan Pengantaran:*\n" .
               "- Penerima: {$customerName}\n" .
               "- No. HP Pelanggan: {$customerPhone}\n" .
               "- Alamat Tujuan: {$address}\n" .
               "- Estimasi Jarak: {$dist}\n" .
               "- Rute Maps: {$mapsLink}\n\n" .
               "Silakan cek kelengkapan barang di toko sebelum berangkat dan berangkatlah tepat waktu agar pesanan tiba dengan selamat. Semangat bertugas!";
    }

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

        $message = $this->buildCustomerMessage($order, $newStatus);
        if (empty($message)) {
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

        $message = $this->buildCourierMessage($order, $courier);

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
