<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('recommendations')) {
            return;
        }

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 8, 4)->nullable();
            $table->string('algorithm', 100);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'generated_at']);
            $table->index(['product_id', 'algorithm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
