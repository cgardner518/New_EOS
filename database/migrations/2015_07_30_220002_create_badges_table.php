<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_badges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->references('id')->on('menu_items');
            $table->string('user_id');
            $table->boolean('is_closed')->nullable();
            $table->string('text')->nullable();
            $table->string('color')->nullable();
            $table->string('auto_reset')->nullable();
            $table->timestamp('reset_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We don't need no stinking badges
        Schema::drop('menu_badges');
    }
}
