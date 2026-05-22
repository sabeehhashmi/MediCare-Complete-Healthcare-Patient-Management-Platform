<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('website_services', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('desc');
            $table->enum('icon_type', ['image', 'fontawesome'])->default('fontawesome')->after('icon');
        });
    }

    public function down()
    {
        Schema::table('website_services', function (Blueprint $table) {
            $table->dropColumn(['icon', 'icon_type']);
        });
    }
};