<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_shipping']);
            $table->decimal('discount_value', 10, 2); // 15 for 15%, 25000 for Rp 25.000, 100 for 100% ongkir
            $table->integer('max_discount')->nullable(); // Maksimal potongan (cth: Rp 20.000 untuk WELCOME15)
            $table->integer('min_subtotal')->default(0); // Syarat min subtotal belanja (cth: Rp 150.000 untuk HEMAT25)
            $table->decimal('max_distance_km', 8, 2)->nullable(); // Maksimal jarak KM (cth: 10 KM untuk ONGKIR10KM)
            $table->boolean('first_order_only')->default(false); // Khusus pesanan pertama user
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

