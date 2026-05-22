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
        Schema::create('notification_list', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->text('user_types')->nullable(); // JSON stored as text: [5, 7, 8]
            $blueprint->longText('user_ids')->nullable(); // JSON stored as text: [1, 2, 3] or "all"
            $blueprint->string('title')->nullable();
            $blueprint->text('description')->nullable();
            $blueprint->string('status')->default('pending'); // pending, processing, completed
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_list');
    }
};
