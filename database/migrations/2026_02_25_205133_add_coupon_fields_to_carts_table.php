<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('applied_coupon_id')->nullable()->after('total');
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('applied_coupon_id');
            $table->json('coupon_data')->nullable()->after('coupon_discount');

            // Optional: Add foreign key if you have coupons table
            $table->foreign('applied_coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['applied_coupon_id']);
            $table->dropColumn(['applied_coupon_id', 'coupon_discount', 'coupon_data']);
        });
    }
};