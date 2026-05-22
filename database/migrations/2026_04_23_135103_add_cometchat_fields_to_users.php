// database/migrations/2024_01_01_000001_add_cometchat_fields_to_users.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCometchatFieldsToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cometchat_uid')->nullable()->after('id');
            $table->boolean('cometchat_user_created')->default(false)->after('cometchat_uid');
            $table->timestamp('cometchat_created_at')->nullable()->after('cometchat_user_created');
            
            $table->index('cometchat_user_created');
            
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cometchat_uid', 'cometchat_user_created', 'cometchat_created_at']);
            $table->dropIndex(['cometchat_user_created']);
            
        });
    }
}