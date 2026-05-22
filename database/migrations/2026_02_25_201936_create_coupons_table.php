<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title_en');
            $table->string('title_ar')->nullable();
            $table->string('title_bn')->nullable();
            $table->text('description')->nullable();
            
            // Coupon Type
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable(); // For percentage type max discount
            
            // Usage Limits
            $table->integer('total_uses')->nullable(); // null = unlimited
            $table->integer('per_user_uses')->default(1);
            $table->integer('used_count')->default(0);
            
            // Date Range
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            // Minimum Order Value
            $table->decimal('min_order_amount', 10, 2)->nullable();
            
            // User Restrictions
            $table->boolean('for_new_users_only')->default(false);
            $table->boolean('for_first_order_only')->default(false);
            
            // Product/Category Restrictions
            $table->enum('apply_on', ['all', 'specific_products', 'specific_categories'])->default('all');
            // Will use pivot tables for specific products/categories
            
            // Status
            $table->boolean('status')->default(true);
            
            // Metadata
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('code');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });

        // Pivot table for coupon products
        Schema::create('coupon_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['coupon_id', 'medicine_id']);
        });

        // Pivot table for coupon categories
        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['coupon_id', 'medicine_category_id']);
        });

        // Table to track coupon usage
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
            
            $table->index(['coupon_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupons');
    }
}