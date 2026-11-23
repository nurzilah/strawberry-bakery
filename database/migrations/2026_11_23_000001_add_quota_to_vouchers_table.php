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
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'quota')) {
                $table->integer('quota')->nullable()->after('max_distance_km');
            }
            if (!Schema::hasColumn('vouchers', 'used_count')) {
                $table->integer('used_count')->default(0)->after('quota');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['quota', 'used_count']);
        });
    }
};

