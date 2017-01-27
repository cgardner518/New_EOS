<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
        Schema:: create('permission_role', function(Blueprint $table) {
			$table->integer('permission_id')->unsigned();
			$table->integer('role_id')->unsigned();
			
			// $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
			// $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

			$table->primary(['permission_id', 'role_id']);
		});
		
		Schema:: create('role_user', function(Blueprint $table) {
			$table->integer('role_id')->unsigned();
			$table->string('user_id', 36);
			
			// $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			
			// Dropping this foriegn key so this can point to pki_users table and such.
			// $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			$table->primary(['role_id', 'user_id']);
		});
		
		// Set up the first permission role link
		DB::table('permission_role')->insert(
            array(
				'permission_id' => 4,
                'role_id' => 1
            )
        );

		// Set up the first user with a roll
		DB::table('role_user')->insert(
            array(
				'role_id' => 1,
                'user_id' => '855bf786-c83c-11e5-a306-08002777c33d'
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
        Schema::drop('permission_role');
		Schema::drop('role_user');
    }
}
