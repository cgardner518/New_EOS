<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventIdToUiState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ui_states', function($table)
		{	
		    $table->integer('element_id')->nullable();
			$table->index(['element', 'element_id'], 'element_element_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ui_states', function($table)
		{
//			$table->dropIndex('element_element_id');
		    $table->dropColumn('element_id');
		});
    }
}
