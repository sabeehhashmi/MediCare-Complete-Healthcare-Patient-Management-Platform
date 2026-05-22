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
        Schema::create('durations', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("title_ar")->nullable();
            $table->string("title_ban")->nullable();
            $table->integer("status")->default(1);
            $table->integer("created_by")->default(0);
            $table->integer("last_updated_by")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('frequencies', function (Blueprint $table) {
            //
            $table->string("title_ban")->nullable();
        });
        Schema::table('directions', function (Blueprint $table) {
            //
            $table->string("title_ban")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('durations');
    }
};
