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
            $table->timestamp('reminder_30m_sent_at')->nullable()->after('urgent_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->dropColumn('reminder_30m_sent_at');
        });
    }
};
