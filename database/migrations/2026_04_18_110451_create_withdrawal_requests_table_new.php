<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign key first
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['withdrawal_id']); 
            // or actual column name used in your DB
        });
    
        // 2. Now safe to drop main table
        Schema::dropIfExists('withdrawal_requests');
    
        // 3. Recreate table
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->decimal('amount', 12, 2);
    
            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'rejected',
                'cancelled'
            ])->default('pending');
    
            $table->string('payment_method')->nullable();
            $table->text('account_details')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
    
            $table->unsignedBigInteger('approved_by')->nullable();
    
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
    
            $table->string('transaction_id')->nullable();
    
            $table->timestamps();
    
            $table->index(['doctor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};