<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEosToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('eos_requests', function($table){
          $table->string('name')->nullable()->change();
          $table->integer('number_of_parts')->nullable()->change();
          $table->integer('project_id')->nullable()->change();
          $table->dropColumn(['clean','hinges','threads','dimX','dimY','dimZ','stl']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
