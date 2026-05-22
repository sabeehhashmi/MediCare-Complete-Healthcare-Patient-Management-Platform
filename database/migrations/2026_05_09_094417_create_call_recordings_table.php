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
       Schema::create('call_recordings', function (Blueprint $table) {
        $table->id();

        $table->string('channel');
        $table->string('sid')->nullable();
        $table->string('resource_id')->nullable();

        $table->text('file_url')->nullable();

        $table->enum('status', ['recording', 'completed'])
            ->default('recording');

        $table->timestamps();
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
