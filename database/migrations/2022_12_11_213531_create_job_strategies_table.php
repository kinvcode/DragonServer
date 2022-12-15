<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_strategies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->comment('策略类别');
            $table->string('name')->default('')->comment('策略名称');
            $table->json('raw')->comment('json数据');
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
        Schema::dropIfExists('job_strategies');
    }
}
