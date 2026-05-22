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
        Schema::table('settings', function (Blueprint $table) {

            $table->string('loyallty_points_enable')->nullable()->default(1);
            $table->string('loyallty_points_amount')->nullable();
            $table->string('loyallty_points_on_amount')->nullable();
            $table->string('loyallty_points_for_percentage')->nullable();
            $table->string('loyallty_points_percentage')->nullable();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_recordings', function (Blueprint $table) {
            //
        });
    }
};
