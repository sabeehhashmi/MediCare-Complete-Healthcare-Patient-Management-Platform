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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->smallInteger('status')->default(0)->nullable()->comment('0=inactive, 1=active');
            $table->smallInteger('is_admin_role')->default(0)->nullable()->comment('0=not admin role, 1=admin role');
            $table->timestamps();
            $table->softDeletes();
        });

        App\Models\Role::create([
            'role' => 'Super Admin',
            'status' => '1',
            'is_admin_role' => '1'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
