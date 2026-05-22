<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeUnavailableDateInDoctorTemporaryUnavailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Truncate the table
        DB::table('doctor_temporary_unavailables')->truncate();

        DB::statement('ALTER TABLE doctor_temporary_unavailables ALTER COLUMN unavailable_date TYPE date USING unavailable_date::date');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Change the datatype back to varchar
        Schema::table('doctor_temporary_unavailables', function (Blueprint $table) {
            $table->string('unavailable_date')->change();
        });
    }
}
