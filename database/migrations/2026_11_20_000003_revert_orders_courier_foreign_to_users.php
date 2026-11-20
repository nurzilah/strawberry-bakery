<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['courier_id']);
            } catch (\Throwable $e) {}
        });

        // Set courier_id to null if the ID does not exist in users table
        DB::statement("
            UPDATE orders 
            SET courier_id = NULL 
            WHERE courier_id IS NOT NULL 
              AND courier_id NOT IN (SELECT id FROM users)
        ");

        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->foreign('courier_id')->references('id')->on('users')->nullOnDelete();
            } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['courier_id']);
            } catch (\Throwable $e) {}
        });
    }
};

