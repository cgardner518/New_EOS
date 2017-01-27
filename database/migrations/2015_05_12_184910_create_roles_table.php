<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('name')->unique();
			$table->integer('smartgroup')->nullable()->unsigned();
			$table->string('smartgroup_description')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert(
            array(
				'label' => 'Developers',
                'name' => 'developers',
                'description' => 'Developers of this application.',
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
        Schema::drop('roles');
    }
}
