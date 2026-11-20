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
        Schema::create('promo_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('scope', ['order', 'package'])->default('order'); // order = pembelian reguler, package = pembelian box/paket
            $table->integer('min_qty'); // syarat minimal qty (pcs untuk order, box untuk package)
            $table->integer('free_qty')->nullable(); // gratis berapa unit
            $table->decimal('discount_percent', 5, 2)->nullable(); // diskon persen dari subtotal
            $table->decimal('free_ongkir_max_km', 8, 2)->nullable(); // gratis ongkir sampai jarak sekian km, null = tanpa batas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_rules');
    }
};
