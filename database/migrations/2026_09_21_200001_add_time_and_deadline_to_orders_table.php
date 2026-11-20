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
            // Jam yang dipilih customer untuk pengambilan/pengantaran
            $table->time('requested_time')->nullable()->after('requested_date');

            // Deadline pengambilan (hanya diisi untuk metode pickup).
            // Dihitung otomatis: requested_date + requested_time + config('store.pickup_deadline_hours')
            $table->dateTime('pickup_deadline_at')->nullable()->after('requested_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['requested_time', 'pickup_deadline_at']);
        });
    }
};
