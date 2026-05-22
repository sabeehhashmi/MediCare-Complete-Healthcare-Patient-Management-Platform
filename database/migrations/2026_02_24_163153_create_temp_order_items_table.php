<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temp_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('medicine_name');
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->boolean('prescription_required')->default(false);
            $table->json('medicine_details')->nullable();
            $table->timestamps();
            
            $table->index(['temp_order_id', 'medicine_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_order_items');
    }
};