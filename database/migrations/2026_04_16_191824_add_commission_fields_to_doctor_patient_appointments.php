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
            $table->enum('commission_status', ['pending', 'approved', 'paid', 'rejected'])->default('pending')->after('doctor_earning');
            $table->unsignedBigInteger('commission_approved_by')->nullable()->after('commission_status');
            $table->timestamp('commission_approved_at')->nullable()->after('commission_approved_by');
            $table->date('commission_payment_date')->nullable()->after('commission_approved_at');
            $table->string('commission_transaction_id')->nullable()->after('commission_payment_date');
            $table->text('commission_notes')->nullable()->after('commission_transaction_id');
            
            $table->foreign('commission_approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->dropForeign(['commission_approved_by']);
            $table->dropColumn([
                'commission_status',
                'commission_approved_by',
                'commission_approved_at',
                'commission_payment_date',
                'commission_transaction_id',
                'commission_notes'
            ]);
        });
    }
};
