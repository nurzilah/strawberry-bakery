<?php

namespace Database\Seeders;

use App\Models\PromoRule;
use Illuminate\Database\Seeder;

class OrderPromoSeeder extends Seeder
{
    /**
     * Seed the regular order discount tiers.
     */
    public function run(): void
    {
        $promos = [
            [
                'name' => 'Diskon Pembelian 10+ Roti',
                'min_qty' => 10,
                'discount_percent' => 5,
                'free_qty' => null,
                'free_ongkir_max_km' => null,
                'bonus_item_name' => null,
                'bonus_item_qty' => null,
            ],
            [
                'name' => 'Diskon Pembelian 25+ Roti',
                'min_qty' => 25,
                'discount_percent' => 10,
                'free_qty' => null,
                'free_ongkir_max_km' => null,
                'bonus_item_name' => null,
                'bonus_item_qty' => null,
            ],
            [
                'name' => 'Diskon Pembelian 50+ Roti',
                'min_qty' => 50,
                'discount_percent' => 15,
                'free_qty' => null,
                'free_ongkir_max_km' => null,
                'bonus_item_name' => null,
                'bonus_item_qty' => null,
            ],
        ];

        foreach ($promos as $promo) {
            PromoRule::updateOrCreate(
                ['name' => $promo['name'], 'scope' => 'order'],
                array_merge($promo, [
                    'scope' => 'order',
                    'is_active' => true,
                ])
            );
        }
    }
}

