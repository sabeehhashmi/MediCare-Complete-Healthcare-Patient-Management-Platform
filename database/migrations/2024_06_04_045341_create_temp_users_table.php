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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->text("user_image")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->integer("gender")->nullable()->comment("1-Male,2-Female,3-Others");
            $table->date('dob')->nullable();
            $table->string("email")->nullable();
            $table->string("dial_code");
            $table->string("phone");
            $table->string("whatsap_dial_code")->nullable();
            $table->string("whatsap_phone")->nullable();
            $table->integer("insurence_id")->default(0);
            $table->integer("sub_insurence_id")->default(0);
            $table->string("phone_otp")->nullable();
            $table->string("email_otp")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_users');
    }
};
