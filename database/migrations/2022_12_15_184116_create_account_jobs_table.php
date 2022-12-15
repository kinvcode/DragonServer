<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('account')->comment('游戏账号');
            $table->date('job_date')->comment('任务日期');
            $table->json('raw')->comment('任务详细数据');
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
        Schema::dropIfExists('account_jobs');
    }
}
