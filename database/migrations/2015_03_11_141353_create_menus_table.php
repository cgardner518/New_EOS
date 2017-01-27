<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('label');
            $table->string('name');
            $table->string('type')->nullable();
            $table->integer('permission_id')->unsigned();
            $table->boolean('active')->nullable();

            // $table->foreign('permission_id')->references('id')->on('permissions');

        });
        
        DB::table('menus')->insert(
            array(
                'label' => 'Site Menu',
                'name' => 'siteMenu',
                'type' => 'site',
                'active' => true,
                'permission_id' => 1,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menus')->insert(
            array(
                'label' => 'User Menu',
                'name' => 'userMenu',
                'type' => 'user',
                'active' => true,
                'permission_id' => 1,
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
        Schema::drop('menus');
    }
}
