<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToDoctorPatientAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('id'); // Adjust the position as needed

            // If you have a users table and want to set up a foreign key constraint:
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            // Drop the foreign key first if it exists
            $table->dropForeign(['created_by']);
            // Then drop the column
            $table->dropColumn('created_by');
        });
    }
}
