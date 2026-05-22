<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(10.00);
            $table->decimal('total', 10, 2);
            $table->string('prescription_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_method')->default('stripe');
            $table->string('payment_intent_id')->nullable();
            $table->string('session_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=processing,2=completed,3=failed');
            $table->json('cart_data')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('session_id');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_orders');
    }
};