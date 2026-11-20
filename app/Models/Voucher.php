<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'discount_value',
        'max_discount',
        'min_subtotal',
        'max_distance_km',
        'first_order_only',
        'is_active',
    ];

    protected $casts = [
        'discount_value'   => 'float',
        'max_discount'     => 'integer',
        'min_subtotal'     => 'integer',
        'max_distance_km'  => 'float',
        'first_order_only' => 'boolean',
        'is_active'        => 'boolean',
    ];

    /**
     * Check if a voucher is eligible for the given user, subtotal, and delivery method.
     *
     * @param User|null $user
     * @param int|float $subtotal
     * @param string $deliveryMethod ('pickup'|'delivery')
     * @param float|null $distanceKm
     * @param int|null $excludeOrderId
     * @return array ['eligible' => bool, 'message' => string]
     */
    public function checkEligibility($user, $subtotal, string $deliveryMethod = 'pickup', ?float $distanceKm = null, ?int $excludeOrderId = null): array
    {
        if (!$this->is_active) {
            return [
                'eligible' => false,
                'message'  => 'Voucher sudah tidak aktif.',
            ];
        }

        // 1. Syarat First Order Only (WELCOME15)
        if ($this->first_order_only) {
            if (!$user) {
                return [
                    'eligible' => false,
                    'message'  => 'Silakan login terlebih dahulu untuk menggunakan voucher ini.',
                ];
            }

            $hasOrders = Order::where('user_id', $user->id)
                ->when($excludeOrderId, fn($q) => $q->where('id', '!=', $excludeOrderId))
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($hasOrders) {
                return [
                    'eligible' => false,
                    'message'  => 'Voucher ' . $this->code . ' hanya berlaku untuk pesanan pertama Anda.',
                ];
            }
        }

        // 2. Syarat Free Shipping (ONGKIR10KM)
        if ($this->type === 'free_shipping') {
            if ($deliveryMethod !== 'delivery') {
                return [
                    'eligible' => false,
                    'message'  => 'Voucher ' . $this->code . ' hanya berlaku jika metode pengiriman Diantar (Delivery) dipilih.',
                ];
            }

            if (!is_null($this->max_distance_km) && !is_null($distanceKm) && $distanceKm > $this->max_distance_km) {
                return [
                    'eligible' => false,
                    'message'  => "Voucher " . $this->code . " hanya berlaku untuk jarak maksimal {$this->max_distance_km} KM (Jarak Anda: {$distanceKm} km).",
                ];
            }
        }

        // 3. Syarat Minimal Subtotal (HEMAT25)
        if ($this->min_subtotal > 0 && $subtotal < $this->min_subtotal) {
            $formattedMin = 'Rp ' . number_format($this->min_subtotal, 0, ',', '.');
            return [
                'eligible' => false,
                'message'  => "Minimal subtotal belanja {$formattedMin} untuk menggunakan voucher " . $this->code . ".",
            ];
        }

        return [
            'eligible' => true,
            'message'  => 'Voucher memenuhi syarat dan dapat digunakan.',
        ];
    }

    /**
     * Calculate discount amount.
     *
     * @param int|float $subtotal
     * @param int $shippingCost
     * @param User|null $user
     * @param string $deliveryMethod
     * @param float|null $distanceKm
     * @return int
     */
    public function calculateDiscount($subtotal, int $shippingCost, $user = null, string $deliveryMethod = 'pickup', ?float $distanceKm = null): int
    {
        $check = $this->checkEligibility($user, $subtotal, $deliveryMethod, $distanceKm);
        if (!$check['eligible']) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = (int) round(($this->discount_value / 100) * $subtotal);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return min($discount, (int) $subtotal);
        }

        if ($this->type === 'fixed') {
            return min((int) $this->discount_value, (int) $subtotal);
        }

        if ($this->type === 'free_shipping') {
            return (int) $shippingCost;
        }

        return 0;
    }
}

