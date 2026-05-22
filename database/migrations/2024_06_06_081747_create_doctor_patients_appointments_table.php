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
        Schema::create('doctor_patient_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("doctor_id");
            $table->string("booking_id")->nullable();
            $table->string("booking_time_slot")->nullable();
            $table->string("booking_status")->nullable();
            $table->string("booking_date")->nullable();
            $table->string("reason_cancel")->nullable();
            $table->string("reason_reschedule")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_patients_appointments');
    }
};
