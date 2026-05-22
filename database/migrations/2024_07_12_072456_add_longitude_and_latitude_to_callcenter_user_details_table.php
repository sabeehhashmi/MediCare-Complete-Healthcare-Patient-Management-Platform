<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLongitudeAndLatitudeToCallcenterUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('callcenter_user_details', function (Blueprint $table) {
            $table->decimal('longitude', 10, 7)->nullable()->after('some_existing_column'); // Adjust the precision and scale as needed
            $table->decimal('latitude', 10, 7)->nullable()->after('longitude'); // Adjust the precision and scale as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('callcenter_user_details', function (Blueprint $table) {
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }
}
