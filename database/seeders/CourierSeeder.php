<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'budi',
                'email' => 'jilaaa.scorpio@gmail.com',
                'phone' => '0895324517667',
                'role' => 'kurir',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ],
            [
                'name' => 'Agus Pratama',
                'email' => 'agus.kurir@gmail.com',
                'phone' => '085712345678',
                'role' => 'kurir',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ],
        ];

        foreach ($couriers as $courier) {
            User::updateOrCreate(
                ['phone' => $courier['phone']],
                $courier
            );
        }
    }
}
