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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("hospital_id")->default(0);
            $table->string("year_of_experiance")->nullable();
            $table->string("license_no")->nullable();
            $table->json("license_type_id")->default(0)->nullable();
            $table->integer("country_id")->default(0);  
            $table->string("appointment_dial_code")->nullable();
            $table->string("appointment_phone")->nullable();
            $table->integer("country_of_orgin")->default(0)->nullable();
            $table->integer("gender")->default(1)->comment("1-male,2-female");
            $table->integer("insurence_id")->default(0)->nullable();
            $table->integer("sub_insurence_id")->default(0)->nullable();
            $table->text("profile_desciription")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
