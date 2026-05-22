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
        Schema::create('contact_us_entries', function (Blueprint $table) {
          
            // id
            $table->id();

            // name
            $table->string('name')->nullable();

            // email
            $table->string('email')->nullable();

            // phone
            $table->string('phone')->nullable();
            
            // subject
            $table->string('subject')->nullable();

            // message
            $table->string('message', 4000)->nullable();

            // timepstamp
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_us_entries', function (Blueprint $table) {
            //
        });
    }
};
