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
        Schema::create('hospital_doctor_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer('doctor_id');
            $table->integer('hospital_id');
            $table->integer('user_id');
            $table->decimal('rating');
            $table->string('feeback_message')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_doctor_feedback');
    }
};
