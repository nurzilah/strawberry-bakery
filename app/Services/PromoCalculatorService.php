<?php

namespace App\Services;

use App\Models\PromoRule;

class PromoCalculatorService
{
    /**
     * @return array ['discount_amount' => int, 'free_qty' => int, 'free_ongkir' => bool, 'rule' => ?PromoRule]
     */
    public function calculate(string $scope, int $totalQty, int $subtotal, ?float $distanceKm = null): array
    {
        $rule = PromoRule::where('scope', $scope)
            ->where('is_active', true)
            ->where('min_qty', '<=', $totalQty)
            ->orderBy('min_qty', 'desc')
            ->first();

        if (!$rule) {
            return [
                'discount_amount' => 0,
                'free_qty' => 0,
                'free_ongkir' => false,
                'bonus_item_name' => null,
                'bonus_item_qty' => 0,
                'rule' => null
            ];
        }

        $discountAmount = 0;
        if ($rule->discount_percent) {
            $discountAmount = (int) ($subtotal * ($rule->discount_percent / 100));
        }

        $freeOngkir = false;
        if (!is_null($rule->free_ongkir_max_km) && $rule->free_ongkir_max_km > 0) {
            if (is_null($distanceKm) || $distanceKm <= $rule->free_ongkir_max_km) {
                $freeOngkir = true;
            }
        }

        return [
            'discount_amount' => $discountAmount,
            'free_qty' => $rule->free_qty ?? 0,
            'free_ongkir' => $freeOngkir,
            'bonus_item_name' => $rule->bonus_item_name,
            'bonus_item_qty' => $rule->bonus_item_name ? (int) ($rule->bonus_item_qty ?? 0) : 0,
            'rule' => $rule
        ];
    }
}
