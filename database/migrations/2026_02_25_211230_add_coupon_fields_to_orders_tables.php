<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponFieldsToOrdersTables extends Migration
{
    public function up()
    {
        // Add to temp_orders
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->foreignId('applied_coupon_id')->nullable()->after('notes')
                  ->constrained('coupons')->nullOnDelete();
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('applied_coupon_id');
            $table->json('coupon_data')->nullable()->after('coupon_discount');
        });

        // Add to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('applied_coupon_id')->nullable()->after('notes')
                  ->constrained('coupons')->nullOnDelete();
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('applied_coupon_id');
            $table->json('coupon_data')->nullable()->after('coupon_discount');
        });
    }

    public function down()
    {
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->dropForeign(['applied_coupon_id']);
            $table->dropColumn(['applied_coupon_id', 'coupon_discount', 'coupon_data']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['applied_coupon_id']);
            $table->dropColumn(['applied_coupon_id', 'coupon_discount', 'coupon_data']);
        });
    }
}