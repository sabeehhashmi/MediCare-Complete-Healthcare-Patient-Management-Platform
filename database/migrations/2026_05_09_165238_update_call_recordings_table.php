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
      Schema::table('call_recordings', function (Blueprint $table) {
    
    $table->integer('appointment_id')->nullable();
    $table->string('uid')->nullable();

    $table->string('resource_id')->nullable()->change();
    $table->string('sid')->nullable()->change();
    $table->string('channel')->nullable()->change();
    $table->string('status')
          ->default('recording')->change();

    $table->text('file_list')->nullable();

   
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_recordings');
    }
};
