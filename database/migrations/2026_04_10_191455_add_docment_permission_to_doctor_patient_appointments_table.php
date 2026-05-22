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
        
        
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->string('document_permission')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->dropColumn(['document_permission']);
        });
       
    }
};
