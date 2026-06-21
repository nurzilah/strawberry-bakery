<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'rating')) {
                $table->float('rating')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('products', 'reviews_count')) {
                $table->integer('reviews_count')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('reviews_count');
            }
            if (!Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'is_promo')) {
                $table->boolean('is_promo')->default(false)->after('is_new');
            }
            if (!Schema::hasColumn('products', 'pickup_date')) {
                $table->date('pickup_date')->nullable()->after('is_promo');
            }
            if (!Schema::hasColumn('products', 'purchase_count')) {
                $table->integer('purchase_count')->default(0)->after('pickup_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'purchase_count')) {
                $table->dropColumn('purchase_count');
            }
            if (Schema::hasColumn('products', 'pickup_date')) {
                $table->dropColumn('pickup_date');
            }
            if (Schema::hasColumn('products', 'is_promo')) {
                $table->dropColumn('is_promo');
            }
            if (Schema::hasColumn('products', 'is_new')) {
                $table->dropColumn('is_new');
            }
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('products', 'reviews_count')) {
                $table->dropColumn('reviews_count');
            }
            if (Schema::hasColumn('products', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
