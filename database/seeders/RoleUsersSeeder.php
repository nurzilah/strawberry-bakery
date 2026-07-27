<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@gmail.com',
                'name' => 'IT Developer Strawberry Bakery',
                'password' => 'password',
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Toko Strawberry Bakery Kota Tegal',
            ],
            [
                'email' => 'owner@gmail.com',
                'name' => 'Owner Strawberry Bakery',
                'password' => 'password',
                'role' => 'owner',
                'phone' => '081234567891',
                'address' => 'Kota Tegal, Jawa Tengah',
            ],
            [
                'email' => 'kasir@gmail.com',
                'name' => 'Kasir Strawberry Bakery',
                'password' => 'password',
                'role' => 'kasir',
                'phone' => '081234567892',
                'address' => 'Stand Kasir Strawberry Bakery',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                ]
            );
        }

        // Customer accounts are intentionally not seeded; they must be created dynamically via the frontend register form or Google OAuth Socialite sign-in callback flow.
    }
}
