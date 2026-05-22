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
        Schema::table('contact_us_settings', function (Blueprint $table) {

            // Add uk location field
            $table->string('uae_phone')->nullable()->after('location');
            $table->string('uae_email')->nullable()->after('location');
            $table->string('uk_phone')->nullable()->after('location');
            $table->string('uk_email')->nullable()->after('location');
            $table->string('uk_location')->nullable()->after('location');
            $table->text('working_hours')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
