<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStlFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stl_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('file_size');
            $table->integer('eos_id');
            $table->string('uploaded_by');
            $table->integer('dimX');
            $table->integer('dimY');
            $table->integer('dimZ');
            $table->boolean('clean')->default(false);
            $table->boolean('hinges')->default(false);
            $table->boolean('threads')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stl_files');
    }
}
