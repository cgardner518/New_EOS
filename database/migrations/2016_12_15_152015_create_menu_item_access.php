<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned();
            $table->integer('record_id')->unsigned();
			$table->integer('access')->comment('0 = Unavailable, 1 = Available, 2 = Hidden');
            $table->timestamps();

			$table->foreign('menu_item_id')->references('id')->on('menu_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Buh bye
        Schema::drop('menu_item_access');
    }
}
