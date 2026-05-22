<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Used to store OTPs for unregistered phone numbers during phone login.
     */
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('points')->nullable();
            $table->timestamps();
        }); 
        
        

       

       
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->nullable()->default(0);
            $table->integer('used_points')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
