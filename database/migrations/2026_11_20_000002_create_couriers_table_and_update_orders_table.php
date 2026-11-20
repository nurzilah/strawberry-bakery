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
        // 1. Create couriers table
        if (!Schema::hasTable('couriers')) {
            Schema::create('couriers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone');
                $table->string('vehicle_type')->default('Motor'); // Motor / Mobil
                $table->string('plate_number')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Drop existing foreign key on orders.courier_id (previously referencing users.id)
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['courier_id']);
            } catch (\Throwable $e) {
                // Ignore if foreign key doesn't exist
            }
        });

        // Set existing courier_id to null so it doesn't violate new foreign key to couriers
        DB::table('orders')->update(['courier_id' => null]);

        // 3. Add courier_name and courier_phone columns if not exist
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('courier_id');
            }
            if (!Schema::hasColumn('orders', 'courier_phone')) {
                $table->string('courier_phone')->nullable()->after('courier_name');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('delivery_status');
            }
        });

        // 4. Modify delivery_status column to VARCHAR(50) so it supports 'menunggu_kurir', 'dalam_pengantaran', 'terkirim'
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `delivery_status` VARCHAR(50) NULL DEFAULT 'menunggu_kurir'");

        // Map existing delivery status values
        DB::statement("UPDATE `orders` SET `delivery_status` = 'menunggu_kurir' WHERE `delivery_status` IN ('belum_ditugaskan', 'ditugaskan', 'diambil_kurir') OR `delivery_status` IS NULL");
        DB::statement("UPDATE `orders` SET `delivery_status` = 'dalam_pengantaran' WHERE `delivery_status` = 'dalam_perjalanan'");

        // 5. Add foreign key on orders.courier_id referencing couriers.id
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->foreign('courier_id')->references('id')->on('couriers')->nullOnDelete();
            } catch (\Throwable $e) {
                // Ignore if already exists
            }
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

            if (Schema::hasColumn('orders', 'courier_name')) {
                $table->dropColumn('courier_name');
            }
            if (Schema::hasColumn('orders', 'courier_phone')) {
                $table->dropColumn('courier_phone');
            }
        });

        Schema::dropIfExists('couriers');
    }
};

