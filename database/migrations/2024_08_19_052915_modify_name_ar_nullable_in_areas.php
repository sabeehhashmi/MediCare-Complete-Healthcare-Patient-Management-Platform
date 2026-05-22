<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('name_ar')->nullable(false)->change();
        });
    }
};
