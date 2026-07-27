<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('postal_code', 5)->nullable()->after('district');
            $table->text('address_note')->nullable()->after('postal_code');
            $table->enum('delivery_preference', ['pickup', 'delivery'])->default('pickup')->after('address_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city', 'district', 'postal_code', 'address_note', 'delivery_preference']);
        });
    }
};
