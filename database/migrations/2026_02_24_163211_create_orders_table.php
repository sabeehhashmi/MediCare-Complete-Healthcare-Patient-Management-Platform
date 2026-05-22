<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
         Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('restrict');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(10.00);
            $table->decimal('total', 10, 2);
            $table->string('prescription_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_method')->default('stripe');
            $table->string('payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->tinyInteger('order_status')->default(0)->comment('0=pending,1=confirmed,2=processing,3=shipped,4=delivered,5=cancelled');
            $table->tinyInteger('payment_status')->default(0)->comment('0=pending,1=paid,2=failed,3=refunded');
            $table->string("ticket_number")->nullable();
            $table->integer("product_id")->nullable();
            $table->date('drow_date')->nullable();
            $table->double("price")->nullable();
            $table->integer("is_winner")->default(0);
            $table->string('product_type')->default('daily');
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('order_status');
            $table->index('payment_status');
            $table->index(['user_id', 'order_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};