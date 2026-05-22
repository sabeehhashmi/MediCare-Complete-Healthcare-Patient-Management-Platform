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
         Schema::table('settings', function (Blueprint $table) {
            $table->string('support_email')->nullable()->after('doctor_search_radius');
            $table->string('support_phone')->nullable()->after('support_email');
            $table->decimal('shipping_price', 8, 2)->default(10.00)->after('support_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['support_email', 'support_phone', 'shipping_price']);
        });
    }
};
