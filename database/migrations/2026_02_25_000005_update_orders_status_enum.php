<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_konfirmasi','dikonfirmasi','processing','completed','cancelled') NOT NULL DEFAULT 'menunggu_konfirmasi'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
