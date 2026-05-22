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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("country_id")->default(0);
            $table->integer("emirate_id")->default(0);
            $table->integer("area_id")->default(0);
            $table->text("address")->nullable();
            $table->string("website")->nullable();
            $table->text("profile_description")->nullable();
            $table->text("trade_licenece")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
