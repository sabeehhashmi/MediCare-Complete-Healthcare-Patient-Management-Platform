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
        // Schema::table('hospital_specialities', function (Blueprint $table) {
        //     $table->integer("hospital_id");
        //     $table->integer("speciality_id");
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('hospital_specialities', function (Blueprint $table) {
        //     $table->dropColumn('hospital_id');
        //     $table->dropColumn('speciality_id');
        // });
    }
};
