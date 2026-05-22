<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();        // English name
            $table->string('name_ar')->nullable(); // Optional Arabic
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::table('doctor_patient_appointments', function (Blueprint $table) {
            $table->string('booking_type')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_types');
    }
};