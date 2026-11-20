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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('requested_date')->nullable()->after('pickup_date');
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup')->after('requested_date');
            $table->text('delivery_address')->nullable()->after('delivery_method');
            $table->integer('shipping_cost')->default(0)->after('delivery_address');
            $table->integer('discount_amount')->default(0)->after('total_amount');
            $table->integer('free_qty_applied')->default(0)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'requested_date',
                'delivery_method',
                'delivery_address',
                'shipping_cost',
                'discount_amount',
                'free_qty_applied'
            ]);
        });
    }
};
