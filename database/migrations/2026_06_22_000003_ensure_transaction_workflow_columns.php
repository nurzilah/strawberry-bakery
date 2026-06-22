<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }

            if (!Schema::hasColumn('order_items', 'subtotal')) {
                $table->integer('subtotal')->default(0)->after('price');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_konfirmasi','dikonfirmasi','waiting_verification','processing','completed','cancelled') NOT NULL DEFAULT 'waiting_verification'");
        }

        DB::table('orders')
            ->where('status', 'menunggu_konfirmasi')
            ->update(['status' => 'waiting_verification']);

        DB::table('orders')
            ->where('status', 'dikonfirmasi')
            ->update(['status' => 'processing']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','waiting_verification','processing','completed','cancelled') NOT NULL DEFAULT 'waiting_verification'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_konfirmasi','dikonfirmasi','waiting_verification','processing','completed','cancelled') NOT NULL DEFAULT 'menunggu_konfirmasi'");
        }

        DB::table('orders')
            ->where('status', 'waiting_verification')
            ->update(['status' => 'menunggu_konfirmasi']);

        DB::table('orders')
            ->where('status', 'processing')
            ->update(['status' => 'dikonfirmasi']);

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'subtotal')) {
                $table->dropColumn('subtotal');
            }

            if (Schema::hasColumn('order_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropUnique(['order_number']);
                $table->dropColumn('order_number');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_konfirmasi','dikonfirmasi','processing','completed','cancelled') NOT NULL DEFAULT 'menunggu_konfirmasi'");
        }
    }
};
