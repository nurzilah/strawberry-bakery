<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['customer', 'admin', 'superadmin', 'kasir'])
                    ->default('customer')
                    ->after('password');
            });

            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('customer','admin','superadmin','kasir') NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('customer','admin','superadmin') NOT NULL DEFAULT 'customer'");
    }
};
