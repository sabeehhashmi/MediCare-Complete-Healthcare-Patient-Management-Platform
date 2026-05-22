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

                $table->boolean('is_call_live')->default(0);
                $table->timestamp('call_started_at')->nullable();

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
