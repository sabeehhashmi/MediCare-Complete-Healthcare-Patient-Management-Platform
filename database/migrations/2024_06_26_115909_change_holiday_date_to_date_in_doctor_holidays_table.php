<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeHolidayDateToDateInDoctorHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('doctor_holidays')->delete();
        // Explicitly cast the column to date
        DB::statement('ALTER TABLE doctor_holidays ALTER COLUMN holiday_date TYPE date USING holiday_date::date');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the column type back to datetime
        DB::statement('ALTER TABLE doctor_holidays ALTER COLUMN holiday_date TYPE timestamp USING holiday_date::timestamp');
    }
}
