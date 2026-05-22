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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("full_name");
            $table->integer("gender")->default(0)->comment("1-Male,2-FEmale,3-Others");
            $table->integer("age")->default(0);
            $table->integer("insurence_id")->default(0);
            $table->integer("sub_insurence_id")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
