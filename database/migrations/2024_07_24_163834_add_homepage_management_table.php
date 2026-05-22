<?php

namespace App\Models;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hp_managements', function (Blueprint $table) {
            $table->id();

            // Columns
            // Meta key and make it unique
            $table->string('meta_key')->unique();
            $table->text('meta_value');
            
            // Index the meta key for better permormance
            $table->index('meta_key');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hp_managements');
    }
};
