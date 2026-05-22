<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {

            $table->boolean('payment_reminder_sent')
                ->default(0)
                ->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {

            $table->dropColumn('payment_reminder_sent');
        });
    }
};