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
            //
            $table->dropColumn('platform_fee');
            $table->dropColumn('referal_distribution_level_1');
            $table->dropColumn('referal_distribution_level_2');
            $table->dropColumn('referal_distribution_level_3');
            $table->dropColumn('lotery_distribution_level_1');
            $table->dropColumn('lotery_distribution_level_2');
            $table->dropColumn('lotery_distribution_level_3');
            $table->dropColumn('lotery_distribution_level_4');
            $table->dropColumn('lotery_distribution_level_5');
            $table->dropColumn('lotery_distribution_level_6');
            $table->dropColumn('lotery_distribution_level_7');
            $table->dropColumn('lotery_distribution_level_8');
            $table->dropColumn('lotery_distribution_level_9');
            $table->double("doctor_search_radius")->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
            $table->double('platform_fee', 15, 2);
            $table->double('referal_distribution_level_1', 15, 2)->default(0);
            $table->double('referal_distribution_level_2', 15, 2)->default(0);
            $table->double('referal_distribution_level_3', 15, 2)->default(0);
            $table->double('lotery_distribution_level_1', 15, 2)->default(0);
            $table->double('lotery_distribution_level_2', 15, 2)->default(0);
            $table->double('lotery_distribution_level_3', 15, 2)->default(0);
            $table->double('lotery_distribution_level_4', 15, 2)->default(0);
            $table->double('lotery_distribution_level_5', 15, 2)->default(0);
            $table->double('lotery_distribution_level_6', 15, 2)->default(0);
            $table->double('lotery_distribution_level_7', 15, 2)->default(0);
            $table->double('lotery_distribution_level_8', 15, 2)->default(0);
            $table->double('lotery_distribution_level_9', 15, 2)->default(0);
            $table->dropColumn("doctor_search_radius");
        });
    }
};
