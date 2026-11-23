<?php

namespace App\Helpers;

use Carbon\Carbon;

class StoreHoursHelper
{
    /**
     * Generate daftar seluruh slot waktu operasional toko.
     *
     * @return array<string>  Format: ['08:00', '08:30', '09:00', ...]
     */
    public static function generateTimeSlots(): array
    {
        $hours  = config('store.operating_hours', []);
        $start  = $hours['start'] ?? '08:00';
        $end    = $hours['end']   ?? '20:00';
        $step   = (int) ($hours['step'] ?? 30);

        if ($step < 1) {
            $step = 30;
        }

        [$sh, $sm] = array_map('intval', explode(':', $start));
        [$eh, $em] = array_map('intval', explode(':', $end));

        $startMinutes = $sh * 60 + $sm;
        $endMinutes   = $eh * 60 + $em;

        $slots = [];
        for ($m = $startMinutes; $m <= $endMinutes; $m += $step) {
            $slots[] = sprintf('%02d:%02d', intdiv($m, 60), $m % 60);
        }

        return $slots;
    }

    /**
     * Dapatkan daftar slot waktu yang tersedia untuk tanggal tertentu.
     * Jika tanggal = hari ini, slot dihitung berdasarkan waktu persiapan & estimasi perjalanan kurir.
     *
     * @param string|null $date Format: YYYY-MM-DD
     * @param string $deliveryMethod 'pickup' atau 'delivery'
     * @param float|null $distanceKm Jarak dalam KM jika delivery
     * @return array<string>
     */
    public static function getAvailableTimeSlots(?string $date = null, string $deliveryMethod = 'pickup', ?float $distanceKm = null): array
    {
        $allSlots = static::generateTimeSlots();
        if (!$date) {
            return $allSlots;
        }

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->copy()->format('Y-m-d');

        if ($date < $today) {
            return [];
        }

        if ($date > $today) {
            return $allSlots;
        }

        // Jika tanggal adalah HARI INI:
        if ($deliveryMethod === 'delivery') {
            $travelMinutes = ($distanceKm && $distanceKm > 0) ? (int) ceil(($distanceKm / 30.0) * 60) : 15;
            $prepMinutes = 15;
            $bufferMinutes = $travelMinutes + $prepMinutes;
        } else {
            $leadTimeHours = (int) config('store.order_lead_time_hours', 1);
            $bufferMinutes = $leadTimeHours * 60;
        }

        $minAllowedDateTime = $now->copy()->addMinutes($bufferMinutes);

        return array_values(array_filter($allSlots, function ($slot) use ($date, $minAllowedDateTime) {
            $slotDateTime = Carbon::parse("{$date} {$slot}", 'Asia/Jakarta');
            return $slotDateTime->gte($minAllowedDateTime);
        }));
    }

    /**
     * Memeriksa apakah slot waktu yang dipilih valid untuk tanggal tersebut.
     */
    public static function isValidTimeSlot(string $date, string $time, string $deliveryMethod = 'pickup', ?float $distanceKm = null): bool
    {
        $availableSlots = static::getAvailableTimeSlots($date, $deliveryMethod, $distanceKm);
        return in_array($time, $availableSlots, true);
    }
}
