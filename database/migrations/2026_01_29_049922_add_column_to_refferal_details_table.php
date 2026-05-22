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
        Schema::create('refferal_details', function (Blueprint $table) {
            $table->id();
            $table->integer("appointment_id")->nullable();
            $table->integer("refferal_id")->nullable();
            $table->integer("doctor_id")->nullable();
            $table->text("reason")->nullable();
            $table->text("summery")->nullable();
            $table->text("reason_for_second_opinion")->nullable();
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
