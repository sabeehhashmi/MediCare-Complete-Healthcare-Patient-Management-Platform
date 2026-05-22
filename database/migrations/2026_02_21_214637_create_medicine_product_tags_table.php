<?php
// database/migrations/xxxx_xx_xx_create_medicine_product_tags_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicine_product_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->unsignedBigInteger('product_tag_id');
            $table->timestamps();
            
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('product_tag_id')->references('id')->on('product_tags')->onDelete('cascade');
            
            $table->unique(['medicine_id', 'product_tag_id'], 'medicine_tag_unique');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('medicine_product_tags');
    }
};