<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('menu_id')->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('label');
            $table->integer('permission_id')->unsigned()->nullable();
            $table->integer('sequence');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('active');
            
            $table->index('route');
            
            // $table->foreign('menu_id')->references('id')->on('menus');
            // $table->foreign('permission_id')->references('id')->on('permissions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('menus', function ($table) {
//            $table->dropForeign('menus_permission_id_foreign');
//        });
//
//        Schema::table('menu_items', function ($table) { 
//            $table->dropForeign('menu_items_permission_id_foreign');
//            $table->dropForeign('menu_items_menu_id_foreign');
//        });

        Schema::drop('menu_items');
    }
}
