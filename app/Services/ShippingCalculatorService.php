<?php

namespace App\Services;

class ShippingCalculatorService
{
    /**
     * Hitung jarak tempuh jalan darat (Haversine x faktor koreksi 1.3) dalam KM.
     *
     * @param float $destLat
     * @param float $destLng
     * @return float
     */
    public function calculateDistance(float $destLat, float $destLng): float
    {
        $storeLat = (float) config('store.lat', -6.86985);
        $storeLng = (float) config('store.lng', 109.13615);
        $roadFactor = (float) config('store.road_correction_factor', 1.3);

        $earthRadiusKm = 6371.0;

        $latFrom = deg2rad($storeLat);
        $lonFrom = deg2rad($storeLng);
        $latTo = deg2rad($destLat);
        $lonTo = deg2rad($destLng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        $straightDistance = $angle * $earthRadiusKm;
        $roadDistance = $straightDistance * $roadFactor;

        // Dibulatkan 1 desimal di belakang koma (cth: 14.2 km)
        return round($roadDistance, 1);
    }

    /**
     * Hitung biaya ongkir berdasarkan jarak KM.
     * - Jarak <= 5 km: Rp 10.000
     * - Jarak > 5 km : Rp 10.000 + (ceil(jarak - 5) * Rp 2.500)
     *
     * @param float $distanceKm
     * @return int
     */
    public function calculateCost(float $distanceKm): int
    {
        $baseCost = (int) config('store.base_shipping_cost', 10000);
        $costPerKm = (int) config('store.cost_per_km', 2500);

        if ($distanceKm <= 5.0) {
            return $baseCost;
        }

        $extraKm = ceil($distanceKm - 5.0);
        return $baseCost + (int) ($extraKm * $costPerKm);
    }

    /**
     * Cek apakah jarak pengiriman masih di dalam jangkauan maksimal toko (25 km).
     *
     * @param float $distanceKm
     * @return bool
     */
    public function isWithinRadius(float $distanceKm): bool
    {
        $maxRadius = (float) config('store.max_delivery_radius_km', 25.0);
        return $distanceKm <= $maxRadius;
    }

    /**
     * Hitung jarak & ongkir lengkap sekaligus validasi jangkauan.
     *
     * @param float $destLat
     * @param float $destLng
     * @return array
     */
    public function calculateShipping(float $destLat, float $destLng): array
    {
        $distance = $this->calculateDistance($destLat, $destLng);
        $cost = $this->calculateCost($distance);
        $withinRadius = $this->isWithinRadius($distance);
        $maxRadius = (float) config('store.max_delivery_radius_km', 25.0);

        return [
            'distance_km'      => $distance,
            'shipping_cost'    => $cost,
            'is_within_radius' => $withinRadius,
            'max_radius_km'    => $maxRadius,
            'message'          => $withinRadius
                ? 'Lokasi dalam jangkauan pengantaran toko.'
                : "Lokasi pengiriman ({$distance} km) di luar jangkauan pengantaran toko (maksimal {$maxRadius} km). Silakan pilih metode Ambil di Toko.",
        ];
    }
}

