<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_packages', function (Blueprint $table) {
            $table->string('beverage_type')->nullable()->after('is_free');
            $table->unsignedInteger('beverage_price')->default(0)->after('beverage_type');
        });
    }

    public function down(): void
    {
        Schema::table('order_packages', function (Blueprint $table) {
            $table->dropColumn(['beverage_type', 'beverage_price']);
        });
    }
};
