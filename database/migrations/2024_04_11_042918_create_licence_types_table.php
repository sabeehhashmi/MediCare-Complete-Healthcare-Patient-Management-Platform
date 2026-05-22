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
        Schema::create('licence_types', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("title_ar")->nullable();
            $table->integer("status")->default(1);
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
        Schema::dropIfExists('licence_types');
    }
};
