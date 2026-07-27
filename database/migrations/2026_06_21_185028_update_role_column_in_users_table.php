<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateRoleColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Raw SQL agar aman dari isu dependensi Doctrine DBAL di Laravel 10 saat mengubah ENUM
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('customer', 'admin', 'superadmin', 'owner', 'kasir') NOT NULL DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom ke pilihan role bawaan yang lama
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('customer', 'admin', 'superadmin') NOT NULL DEFAULT 'customer'");
    }
}
