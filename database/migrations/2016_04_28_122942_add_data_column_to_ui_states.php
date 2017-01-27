<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataColumnToUiStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ui_states', function ($table) {
	     	$table->text('data')->nullable();
	     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('ui_states', function (Blueprint $table) {

        	$table->dropColumn('data');
	});
    }
}
