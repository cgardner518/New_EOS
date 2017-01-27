<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEosRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('roles')->insert(
            array(
				'label' => 'EOS Staff',
                'name' => 'eosStaff',
                'description' => 'Paid employees of LASR',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );
        DB::table('permissions')->insert(
            array(
				'label' => 'EOS Admin',
                'name' => 'eosAdmin',
                'description' => 'Generally for EOS Staff who print the STL files',
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );
        DB::table('permission_role')->insert(
                array(
    				'permission_id' => 5,
                    'role_id' => 2
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
        DB::table('permissions')->where('name', '=', 'eosAdmin')->delete();
        DB::table('roles')->where('name', '=', 'eosStaff')->delete();
        DB::table('permission_role')->where('role_id', '=', 2)->delete();
    }
}
