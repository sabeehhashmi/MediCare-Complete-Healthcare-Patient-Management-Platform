<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {

            if (!Schema::hasColumn('doctor_patient_appointments', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false)->after('booking_status');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('is_urgent');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'consultation_fee')) {
                $table->decimal('consultation_fee', 10, 2)->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'admin_commission')) {
                $table->decimal('admin_commission', 10, 2)->nullable()->after('consultation_fee');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'payment_token')) {
                $table->string('payment_token', 100)->nullable()->after('admin_commission');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable()->after('payment_token');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'payment_completed_at')) {
                $table->timestamp('payment_completed_at')->nullable()->after('stripe_session_id');
            }

            if (!Schema::hasColumn('doctor_patient_appointments', 'urgent_notified_at')) {
                $table->timestamp('urgent_notified_at')->nullable()->after('payment_completed_at');
            }

        });
    }

    public function down()
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {

            $columns = [
                'is_urgent',
                'payment_status',
                'consultation_fee',
                'admin_commission',
                'payment_token',
                'stripe_session_id',
                'payment_completed_at',
                'urgent_notified_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('doctor_patient_appointments', $column)) {
                    $table->dropColumn($column);
                }
            }

        });
    }
};