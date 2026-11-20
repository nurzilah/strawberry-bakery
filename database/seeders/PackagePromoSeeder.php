<?php

namespace Database\Seeders;

use App\Models\PromoRule;
use Illuminate\Database\Seeder;

class PackagePromoSeeder extends Seeder
{
    /**
     * Seed the package discount tiers.
     */
    public function run(): void
    {
        $promos = [
            [
                'name' => 'Diskon 20+ Box',
                'min_qty' => 20,
                'discount_percent' => 5,
                'bonus_item_name' => null,
                'bonus_item_qty' => null,
            ],
            [
                'name' => 'Diskon 30+ Box',
                'min_qty' => 30,
                'discount_percent' => 7,
                'bonus_item_name' => null,
                'bonus_item_qty' => null,
            ],
            [
                'name' => 'Diskon 50+ Box + Air Mineral Gratis',
                'min_qty' => 50,
                'discount_percent' => 10,
                'bonus_item_name' => 'Air Mineral Gelas/Box',
                'bonus_item_qty' => 1,
            ],
            [
                'name' => 'Diskon 100+ Box + Air Mineral Gratis',
                'min_qty' => 100,
                'discount_percent' => 15,
                'bonus_item_name' => 'Air Mineral Gelas/Box',
                'bonus_item_qty' => 1,
            ],
        ];

        foreach ($promos as $promo) {
            PromoRule::updateOrCreate(
                ['name' => $promo['name']],
                array_merge($promo, [
                    'scope' => 'package',
                    'free_qty' => null,
                    'free_ongkir_max_km' => null,
                    'is_active' => true,
                ])
            );
        }
    }
}
