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
        if (Schema::hasTable('department_hospital')) {
            Schema::table('department_hospital', function (Blueprint $table) {
                $table->softDeletes(); // Add soft deletes
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('department_hospital')) {
            Schema::table('department_hospital', function (Blueprint $table) {
                $table->dropSoftDeletes(); // Drop soft deletes
            });
        }
    }
};
