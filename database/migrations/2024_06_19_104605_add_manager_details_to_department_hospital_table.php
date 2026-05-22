<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerDetailsToDepartmentHospitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department_hospital', function (Blueprint $table) {
            $table->string('manager_name')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_hospital', function (Blueprint $table) {
            $table->dropColumn('manager_name');
            $table->dropColumn('dial_code');
            $table->dropColumn('phone');
            $table->dropColumn('email');
        });
    }
}
