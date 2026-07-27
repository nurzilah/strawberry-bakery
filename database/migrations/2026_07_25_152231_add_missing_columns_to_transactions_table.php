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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('user_id');
            }
            if (!Schema::hasColumn('transactions', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('discount');
            }
            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('tax_rate');
            }
            if (!Schema::hasColumn('transactions', 'extra_charge')) {
                $table->decimal('extra_charge', 12, 2)->default(0)->after('tax_amount');
            }
            if (!Schema::hasColumn('transactions', 'extra_charge_label')) {
                $table->string('extra_charge_label')->nullable()->after('extra_charge');
            }
            if (!Schema::hasColumn('transactions', 'rounding')) {
                $table->decimal('rounding', 12, 2)->default(0)->after('extra_charge_label');
            }
            if (!Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('amount_change');
            }
            if (!Schema::hasColumn('transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'tax_rate',
                'tax_amount',
                'extra_charge',
                'extra_charge_label',
                'rounding',
                'notes',
                'paid_at'
            ]);
        });
    }
};
