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
        Schema::create('clinical_assessment_and_documentation', function (Blueprint $table) {
            $table->id();
            $table->integer("appointment_id")->nullable();
            $table->text("symptoms")->nullable();
            $table->text("present_illness")->nullable();
            $table->text("past_history")->nullable();
            $table->integer("created_by")->default(0);
            $table->integer("last_updated_by")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_assessment_and_documentation');
    }
};
