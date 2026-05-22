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
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            //

            $table->string("lab_report")->nullable();
            $table->string("xrays")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            //
        });
    }
};
