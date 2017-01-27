<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menu_items')->insert(
            array(
                'menu_id' => 1,
                'parent_id' => 0,
                'label' => 'Developer',
                'name' => 'developer',
                'sequence' => 1,
                'route' => '',
                'icon' => 'fa-desktop',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

		DB::table('menu_items')->insert(
            array(
                'menu_id' => 1,
                'parent_id' => 1,
                'label' => 'Users',
                'name' => 'users',
                'sequence' => 2,
                'route' => 'users',
                'icon' => '',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menu_items')->insert(
            array(
                'menu_id' => 1,
                'parent_id' => 1,
                'label' => 'Roles',
                'name' => 'roles',
                'sequence' => 3,
                'route' => 'roles',
                'icon' => '',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menu_items')->insert(
            array(
                'menu_id' => 1,
                'parent_id' => 1,
                'label' => 'Permissions',
                'name' => 'permissions',
                'sequence' => 4,
                'route' => 'permissions',
                'icon' => '',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menu_items')->insert(
            array(
                'menu_id' => 1,
                'parent_id' => 1,
                'label' => 'Menus',
                'name' => 'menus',
                'sequence' => 5,
                'route' => 'menus',
                'icon' => '',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        // More menu items
        DB::table('menu_items')->insert(
            array(
                'menu_id' => 2,
                'parent_id' => 0,
                'label' => 'Preferences',
                'name' => 'preferences',
                'sequence' => 1,
                'route' => 'preferences',
                'icon' => 'fa-cogs',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menu_items')->insert(
            array(
                'menu_id' => 2,
                'parent_id' => 0,
                'label' => 'About This Site',
                'name' => 'aboutThisSite',
                'sequence' => 2,
                'route' => 'about',
                'icon' => 'fa-info',
                'active' => true,
                'permission_id' => 4,
                'updated_at'=> date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            )
        );

        DB::table('menu_items')->insert(
            array(
                'menu_id' => 2,
                'parent_id' => 0,
                'label' => 'Log Out',
                'name' => 'logout',
                'sequence' => 3,
                'route' => 'users/logout',
                'icon' => 'fa-sign-out',
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
        DB::table('menu_items')->where('name', '=', 'menus')->delete();
        DB::table('menu_items')->where('name', '=', 'userGroups')->delete();
        DB::table('menu_items')->where('name', '=', 'permissions')->delete();
        DB::table('menu_items')->where('name', '=', 'preferences')->delete();
        DB::table('menu_items')->where('name', '=', 'aboutThisSite')->delete();
        DB::table('menu_items')->where('name', '=', 'logOut')->delete();
    }
}
