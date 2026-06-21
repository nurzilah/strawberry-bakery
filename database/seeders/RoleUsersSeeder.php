<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@strawberrybakery.test'],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@strawberrybakery.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@strawberrybakery.test'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );
    }
}
