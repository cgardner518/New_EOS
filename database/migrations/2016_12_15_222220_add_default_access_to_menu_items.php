<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultAccessToMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function($table) {
            $table->integer('default_access')->unsigned()->default(0);
        });
	
		// Set all the existing rows to 1
		DB::table('menu_items')->update(['default_access'=>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function($table) {
			$table->dropColumn('default_access');
		});
    }
}
