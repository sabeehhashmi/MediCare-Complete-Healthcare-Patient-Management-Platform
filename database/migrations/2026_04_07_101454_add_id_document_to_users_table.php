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
            $table->string('identification_document')->nullable()->after('user_image');
            $table->string('identification_type')->nullable()->after('identification_document'); // national_id, passport, etc.
            $table->string('identification_number')->nullable()->after('identification_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['identification_document', 'identification_type', 'identification_number']);
        });
    }
};
