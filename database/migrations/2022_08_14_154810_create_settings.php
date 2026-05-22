<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();
        });

        DB::table('settings')->insert(
          [
            'platform_fee' => '0.00'
          ]
       );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
