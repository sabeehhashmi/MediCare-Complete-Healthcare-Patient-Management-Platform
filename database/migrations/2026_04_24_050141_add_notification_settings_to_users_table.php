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
            $table->tinyInteger('enable_lab_result_notification')->default(1)->after('enable_public_notification');
            $table->tinyInteger('enable_payment_notification')->default(1)->after('enable_lab_result_notification');
            $table->tinyInteger('enable_prescription_notification')->default(1)->after('enable_payment_notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'enable_lab_result_notification',
                'enable_payment_notification',
                'enable_prescription_notification'
            ]);
        });
    }
};
