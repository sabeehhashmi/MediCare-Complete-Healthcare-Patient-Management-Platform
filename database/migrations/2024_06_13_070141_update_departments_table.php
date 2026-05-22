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
        Schema::table('departments', function (Blueprint $table) {
            // Drop the columns that are no longer needed
            $table->dropColumn([
                'department_name',
                'department_manager',
                'dial_code',
                'phone',
                'email',
                'doctor_id',
                'manager_id',
                'active'
            ]);

            // Add the new columns
            $table->string('title')->nullable();
            $table->string('title_ar')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            // Add the dropped columns back
            $table->string('department_name');
            $table->string('department_manager')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->boolean('active')->default(1);

            // Drop the newly added columns
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
