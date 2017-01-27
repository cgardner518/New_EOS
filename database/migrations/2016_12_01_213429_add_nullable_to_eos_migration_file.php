<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToEosMigrationFile extends Migration
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
          $table->integer('status')->default(0)->nullable()->change();
          $table->boolean('clean')->default(true)->nullable()->change();
          $table->boolean('hinges')->default(true)->nullable()->change();
          $table->boolean('threads')->default(true)->nullable()->change();
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
