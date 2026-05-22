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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->text("access_token")->nullable();
            $table->string("user_device_token")->nullable();
            $table->string("firebase_user_key")->nullable();
            $table->string("device_type")->nullable();
            $table->integer("gender")->nullable()->comment("1-Male,2-Female,3-Others");
            $table->date('dob')->nullable();
            $table->string("whatsap_dial_code")->nullable();
            $table->string("whatsap_phone")->nullable();
            $table->integer("insurence_id")->default(0);
            $table->integer("sub_insurence_id")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn("gender");
            $table->dropColumn("dob");
            $table->dropColumn("whatsap_dial_code");
            $table->dropColumn("whatsap_phone");
            $table->dropColumn("insurence_id");
            $table->dropColumn("sub_insurence_id");
            $table->dropColumn("access_token");
            $table->dropColumn("user_device_token");
            $table->dropColumn("firebase_user_key");
            $table->dropColumn("device_type");
        });
    }
};
