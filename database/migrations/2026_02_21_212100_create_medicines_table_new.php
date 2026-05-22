<?php
// database/migrations/xxxx_xx_xx_create_medicines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('medicines');
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('title_bn');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('medicin_category_id');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->integer('stock_quantity')->default(0);
            $table->string('manufacturer')->nullable();
            $table->boolean('prescription_required')->default(false);
            $table->string('image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('tags')->nullable();
            $table->text('uses')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('benefits')->nullable();
            $table->text('how_to_use')->nullable();
            $table->text('other_info')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};