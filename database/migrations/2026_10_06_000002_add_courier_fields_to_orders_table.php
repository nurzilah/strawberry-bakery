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
            $table->foreignId('courier_id')->nullable()->after('delivery_method')->constrained('users')->nullOnDelete();
            $table->enum('delivery_status', ['belum_ditugaskan', 'ditugaskan', 'diambil_kurir', 'dalam_perjalanan', 'terkirim', 'gagal_kirim'])->default('belum_ditugaskan')->after('courier_id');
            $table->timestamp('delivered_at')->nullable()->after('delivery_status');
            $table->text('delivery_notes')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_id']);
            $table->dropColumn(['courier_id', 'delivery_status', 'delivered_at', 'delivery_notes']);
        });
    }
};
