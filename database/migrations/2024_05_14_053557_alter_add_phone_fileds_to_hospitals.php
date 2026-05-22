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
        Schema::table('hospitals', function (Blueprint $table) {
            //
            $table->string("appointment_dial_code")->nullable();
            $table->string("appointment_phone")->nullable();
            $table->text("profile_description_ar")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            //
            $table->dropColumn("appointment_dial_code");
            $table->dropColumn("appointment_phone");
            $table->dropColumn("profile_description_ar");
        });
    }
};
