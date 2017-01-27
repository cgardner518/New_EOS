<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use NrlLaravel\Labcoat\Models\MenuItem;

class AddRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Set up the Role Permission Table menu item
		if(!MenuItem::where('name','rpTable')->get()->count()) {
			DB::table('menu_items')->insert(
	            array(
					'menu_id' => 1,
	                'parent_id' => 1,
	                'label' => 'Role/Permission Table',
	                'name' => 'rpTable',
	                'sequence' => 1,
	                'route' => 'role-permission-table',
	                'icon' => '',
	                'active' => true,
	                'permission_id' => 4,
	                'updated_at'=> date('Y-m-d H:i:s'),
	                'created_at'=>date('Y-m-d H:i:s'),
	            )
	        );
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menu_items')->where('name', '=', 'rpTable')->delete();
    }
}
