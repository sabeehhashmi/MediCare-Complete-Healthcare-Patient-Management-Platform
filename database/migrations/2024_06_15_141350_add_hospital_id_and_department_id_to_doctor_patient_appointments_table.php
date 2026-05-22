<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHospitalIdAndDepartmentIdToDoctorPatientAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id')->nullable()->after('updated_at');
            $table->unsignedBigInteger('department_id')->nullable()->after('hospital_id');

            // Assuming you have hospitals and departments tables with id as primary key
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
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
            $table->dropForeign(['hospital_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn('hospital_id');
            $table->dropColumn('department_id');
        });
    }
}
