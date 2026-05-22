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
        Schema::create('prescription_details', function (Blueprint $table) {
            $table->id();
            $table->integer("prescription_id")->nullable();
            $table->integer("medicine_id")->nullable();
            $table->integer("direction_id")->nullable();
            $table->integer("frquency_id")->nullable();
            $table->integer("duration_id")->nullable();
            $table->integer("quantity")->nullable();
            $table->text("instructions")->nullable();
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
        Schema::dropIfExists('prescriptions');
    }
};
