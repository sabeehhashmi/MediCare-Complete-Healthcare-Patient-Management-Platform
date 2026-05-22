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
                // Check and add columns if they do not exist
                if (!Schema::hasColumn('department_hospital', 'department_name')) {
                    $table->string('department_name')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'department_manager')) {
                    $table->string('department_manager')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'dial_code')) {
                    $table->string('dial_code')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'doctor_id')) {
                    $table->unsignedBigInteger('doctor_id')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'manager_id')) {
                    $table->unsignedBigInteger('manager_id')->nullable();
                }
                if (!Schema::hasColumn('department_hospital', 'active')) {
                    $table->boolean('active')->default(1);
                }
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
                // Drop the columns if they exist
                if (Schema::hasColumn('department_hospital', 'department_name')) {
                    $table->dropColumn('department_name');
                }
                if (Schema::hasColumn('department_hospital', 'department_manager')) {
                    $table->dropColumn('department_manager');
                }
                if (Schema::hasColumn('department_hospital', 'dial_code')) {
                    $table->dropColumn('dial_code');
                }
                if (Schema::hasColumn('department_hospital', 'phone')) {
                    $table->dropColumn('phone');
                }
                if (Schema::hasColumn('department_hospital', 'email')) {
                    $table->dropColumn('email');
                }
                if (Schema::hasColumn('department_hospital', 'doctor_id')) {
                    $table->dropColumn('doctor_id');
                }
                if (Schema::hasColumn('department_hospital', 'manager_id')) {
                    $table->dropColumn('manager_id');
                }
                if (Schema::hasColumn('department_hospital', 'active')) {
                    $table->dropColumn('active');
                }
                $table->softDeletes();
            });
        }
    }
};
