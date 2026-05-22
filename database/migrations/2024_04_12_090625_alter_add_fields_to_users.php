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
            //
            $table->dropColumn('refferal_code');
            $table->dropColumn('refered_by');
            $table->integer("created_by")->default(0);
            $table->integer("last_updated_by")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('last_updated_by');
            $table->dropColumn('created_by');
            $table->string("refferal_code")->nullable();
            $table->string("refered_by")->nullable();
        });
    }
};
