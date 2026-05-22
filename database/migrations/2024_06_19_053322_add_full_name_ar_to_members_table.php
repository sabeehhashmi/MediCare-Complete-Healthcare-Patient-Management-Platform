<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameArToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('full_name_ar')->nullable()->after('full_name');
            $table->integer('insurence_id')->nullable()->change();
            $table->integer('sub_insurence_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('full_name_ar');
            $table->integer('insurence_id')->nullable(false)->change();
            $table->integer('sub_insurence_id')->nullable(false)->change();
        });
    }
}
