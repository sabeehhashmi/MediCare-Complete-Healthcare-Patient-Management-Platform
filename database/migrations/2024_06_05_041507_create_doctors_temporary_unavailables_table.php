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
        Schema::create('doctor_temporary_unavailables', function (Blueprint $table) {
            $table->id();
            $table->integer("doctor_id");
            $table->string("unavailable_timeslot")->nullable();
            $table->string("unavailable_date")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_temporary_unavailables');
    }
};
