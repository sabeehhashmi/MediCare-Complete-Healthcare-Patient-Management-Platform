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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->integer("doctor_id");
            $table->integer("sunday_availability")->default(0);
            $table->json("sunday_time_slot")->nullable();
            $table->integer("monday_availability")->default(0);
            $table->json("monday_time_slot")->nullable();
            $table->integer("tuesday_availability")->default(0);
            $table->json("tuesday_time_slot")->nullable();
            $table->integer("wednesday_availability")->default(0);
            $table->json("wednesday_time_slot")->nullable();
            $table->integer("thursday_availability")->default(0);
            $table->json("thursday_time_slot")->nullable();
            $table->integer("friday_availability")->default(0);
            $table->json("friday_time_slot")->nullable();
            $table->integer("saturday_availability")->default(0);
            $table->json("saturday_time_slot")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_availability');
    }
};
