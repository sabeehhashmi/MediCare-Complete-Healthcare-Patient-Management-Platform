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
        Schema::create('doctor_reschedule_appointments', function (Blueprint $table) {
            $table->id();

            $table->integer("patient_appointment_id");
            $table->integer("doctor_id");
            $table->string("reschedule_patient_booking_date")->nullable();
            $table->string("reschedule_patient_time_slot")->nullable();
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_reschedule_appointments');
    }
};
