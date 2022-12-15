<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->comment('任务排序');
            $table->bigInteger('strategy_id')->comment('任务策略');
            $table->tinyInteger('type')->comment('分配类型0默认 1角色');
            $table->integer('role_id')->nullable()->comment('角色ID');
            $table->bigInteger('role_account')->nullable()->comment('角色账号');
            $table->string('role_name')->nullable()->comment('角色名称');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
