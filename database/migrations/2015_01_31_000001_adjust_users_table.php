<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class AdjustUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {		
        Schema::table('users', function($table) {
            $table->string('id', 36)->change();
            $table->unique('id');
        });

		DB::table('users')->insert(
            array(
				'id' => '855bf786-c83c-11e5-a306-08002777c33d',
                'name' => 'developer',
                'email' => 'developer',
                'password' => 'temp4now',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropUnique('users_id_unique');
        });
        Schema::table('users', function($table) {
            $table->increments('id')->change();
        });
    }
}