<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'payment_method')) {
            return;
        }

        DB::table('orders')
            ->whereNotIn('payment_method', ['transfer', 'qris', 'midtrans'])
            ->update(['payment_method' => 'transfer']);

        // Skip enum alteration to avoid truncation; a later migration changes to string.
    }

    public function down(): void
    {
        // No-op: schema change is handled by a later migration.
    }
};
