<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_rules', function (Blueprint $table) {
            $table->string('bonus_item_name')->nullable()->after('free_ongkir_max_km');
            $table->unsignedInteger('bonus_item_qty')->nullable()->after('bonus_item_name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('bonus_item_name')->nullable()->after('free_qty_applied');
            $table->unsignedInteger('bonus_item_qty')->nullable()->after('bonus_item_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bonus_item_name', 'bonus_item_qty']);
        });

        Schema::table('promo_rules', function (Blueprint $table) {
            $table->dropColumn(['bonus_item_name', 'bonus_item_qty']);
        });
    }
};
