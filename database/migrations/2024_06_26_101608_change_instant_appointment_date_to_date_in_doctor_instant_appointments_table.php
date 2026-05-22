<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeInstantAppointmentDateToDateInDoctorInstantAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Explicitly cast the column to date
        DB::statement('ALTER TABLE doctor_instant_appointments ALTER COLUMN instant_appointment_date TYPE date USING instant_appointment_date::date');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the column type back to datetime
        DB::statement('ALTER TABLE doctor_instant_appointments ALTER COLUMN instant_appointment_date TYPE timestamp USING instant_appointment_date::timestamp');
    }
}


