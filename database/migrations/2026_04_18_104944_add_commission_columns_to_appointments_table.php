<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_status')) {
                $table->enum('commission_status', ['pending', 'approved', 'paid', 'rejected'])
                      ->default('pending')
                      ->after('doctor_earning');
            }
            
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_approved_by')) {
                $table->unsignedBigInteger('commission_approved_by')->nullable()
                      ->after('commission_status');
            }
            
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_approved_at')) {
                $table->timestamp('commission_approved_at')->nullable()
                      ->after('commission_approved_by');
            }
            
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_payment_date')) {
                $table->date('commission_payment_date')->nullable()
                      ->after('commission_approved_at');
            }
            
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_transaction_id')) {
                $table->string('commission_transaction_id')->nullable()
                      ->after('commission_payment_date');
            }
            
            if (!Schema::hasColumn('doctor_patient_appointments', 'commission_notes')) {
                $table->text('commission_notes')->nullable()
                      ->after('commission_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $columns = ['commission_status', 'commission_approved_by', 'commission_approved_at', 
                       'commission_payment_date', 'commission_transaction_id', 'commission_notes'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('doctor_patient_appointments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};