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
        //
        //DB::statement('ALTER TABLE doctor_patient_appointments ALTER COLUMN booking_date TYPE date USING booking_date::date');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
