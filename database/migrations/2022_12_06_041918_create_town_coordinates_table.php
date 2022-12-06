<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTownCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('town_coordinates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('坐标名称');
            $table->integer('word')->comment('大区域编号');
            $table->integer('area')->comment('小区域编号');
            $table->integer('x')->comment('X坐标');
            $table->integer('y')->comment('Y坐标');
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
        Schema::dropIfExists('town_coordinates');
    }
}
