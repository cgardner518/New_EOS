<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEosMenuItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('menu_items')->insert(
            array(
                'menu_id' => 3,
                'parent_id' => 1,
                'label' => 'EOS Requests',
                'name' => 'eosRequests',
                'sequence' => 0,
                'route' => '/requests',
                'icon' => 'fa-cubes',
                'active' => true,
                'permission_id' => 2,
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
        //
        DB::table('menu_items')->where('name', '=', 'esoRequests')->delete();
    }
}
