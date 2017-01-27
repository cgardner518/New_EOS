<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Schema::drop('permissions');
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('rule')->nullable();
            $table->timestamps();
        });
        
        // Set up the initial permission
        DB::table('permissions')->insert(
            array(
                'label' => '_Public',
                'name' => '_public',
                'description' => 'Items with this permission are accessible by anyone.  The underscore indicates that this is a special permission.',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

		// Set up the initial permission
        DB::table('permissions')->insert(
            array(
                'label' => '_Authenticated',
                'name' => '_authenticated',
                'description' => 'This is only accessible by authenticated users.',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

		// Set up the initial permission
        DB::table('permissions')->insert(
            array(
                'label' => '_Unauthenticated',
                'name' => '_unauthenticated',
                'description' => 'This is only accessible by authenticated users.  The underscore indicates that this is a special permission.',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('permissions')->insert(
            array(
                'label' => 'Access to Developer Tools',
                'name' => 'devToolAccess',
                'description' => 'This allows the user to use the functionality in the Developer menu.  This is typically only granted to developers.',
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
        Schema::drop('permissions');
    }
}
