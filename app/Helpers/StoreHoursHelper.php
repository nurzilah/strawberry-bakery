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
     * Jika tanggal = hari ini, hanya mengembalikan slot >= (jam sekarang + lead_time_hours).
     * Jika tanggal > hari ini, mengembalikan semua slot operasional.
     * Jika tanggal < hari ini, mengembalikan array kosong.
     *
     * @param string|null $date Format: YYYY-MM-DD
     * @return array<string>
     */
    public static function getAvailableTimeSlots(?string $date = null): array
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
        $leadTimeHours = (int) config('store.order_lead_time_hours', 1);
        $minAllowedDateTime = $now->copy()->addHours($leadTimeHours);

        return array_values(array_filter($allSlots, function ($slot) use ($date, $minAllowedDateTime) {
            $slotDateTime = Carbon::parse("{$date} {$slot}", 'Asia/Jakarta');
            return $slotDateTime->gte($minAllowedDateTime);
        }));
    }

    /**
     * Memeriksa apakah slot waktu yang dipilih valid untuk tanggal tersebut.
     */
    public static function isValidTimeSlot(string $date, string $time): bool
    {
        $availableSlots = static::getAvailableTimeSlots($date);
        return in_array($time, $availableSlots, true);
    }
}
