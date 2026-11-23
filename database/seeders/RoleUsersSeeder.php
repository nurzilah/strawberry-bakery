<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = env('DEFAULT_ADMIN_PASSWORD', 'Strawberry2026!');

        $users = [
            [
                'email' => 'itdev@strawberrybakery.test',
                'name' => 'IT Developer Strawberry Bakery',
                'role' => 'admin',
                'phone' => '081200000010',
                'address' => 'Toko Strawberry Bakery Kota Tegal',
            ],
            [
                'email' => 'owner@strawberrybakery.test',
                'name' => 'Owner Strawberry Bakery',
                'role' => 'owner',
                'phone' => '081200000020',
                'address' => 'Kota Tegal, Jawa Tengah',
            ],
            [
                'email' => 'kasir@strawberrybakery.test',
                'name' => 'Kasir Strawberry Bakery',
                'role' => 'kasir',
                'phone' => '081200000030',
                'address' => 'Stand Kasir Strawberry Bakery',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($defaultPassword),
                    'role' => $user['role'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                    'is_verified' => true,
                ]
            );
        }
    }
}