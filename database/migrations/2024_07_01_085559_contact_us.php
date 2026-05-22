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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 400)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('dial_code', 255)->nullable();
            $table->string('mobile', 255)->nullable();
            $table->text('message')->nullable();
            $table->text('reply')->nullable();
            $table->timestamps(true); // Add created_at and updated_at with timestamps
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
