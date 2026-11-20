<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code'             => 'WELCOME15',
                'name'             => 'Diskon 15% Pembelian Pertama',
                'description'      => 'Diskon 15% maksimal potongan Rp 20.000 khusus untuk pembelian pertama pelanggan baru.',
                'type'             => 'percentage',
                'discount_value'   => 15.00,
                'max_discount'     => 20000,
                'min_subtotal'     => 0,
                'max_distance_km'  => null,
                'first_order_only' => true,
                'is_active'        => true,
            ],
            [
                'code'             => 'ONGKIR10KM',
                'name'             => 'Gratis Ongkir Radius 10 KM',
                'description'      => 'Gratis ongkir 100% untuk pengiriman Diantar (Delivery) dengan jarak maksimal 10 KM.',
                'type'             => 'free_shipping',
                'discount_value'   => 100.00,
                'max_discount'     => null,
                'min_subtotal'     => 0,
                'max_distance_km'  => 10.00,
                'first_order_only' => false,
                'is_active'        => true,
            ],
            [
                'code'             => 'HEMAT25',
                'name'             => 'Potongan Belanja Rp 25.000',
                'description'      => 'Potongan langsung Rp 25.000 dengan minimal subtotal belanja Rp 150.000.',
                'type'             => 'fixed',
                'discount_value'   => 25000.00,
                'max_discount'     => null,
                'min_subtotal'     => 150000,
                'max_distance_km'  => null,
                'first_order_only' => false,
                'is_active'        => true,
            ],
        ];

        foreach ($vouchers as $voucherData) {
            Voucher::updateOrCreate(
                ['code' => $voucherData['code']],
                $voucherData
            );
        }
    }
}

