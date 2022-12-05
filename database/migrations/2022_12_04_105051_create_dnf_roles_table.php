<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDnfRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnf_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('favorite')->default(0)->comment('偏爱栏');
            $table->bigInteger('account')->nullable()->comment('账号');
            $table->bigInteger('role_id')->nullable()->comment('角色ID');
            $table->string('name')->nullable()->comment('名称');
            $table->integer('character')->nullable()->comment('职业');
            $table->integer('advancement')->nullable()->comment('转职职业');
            $table->integer('awakening')->nullable()->comment('觉醒状态');
            $table->integer('level')->nullable()->comment('等级');
            $table->integer('prestige')->nullable()->comment('名望');
            $table->integer('position')->nullable()->comment('角色栏位置');
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
        Schema::dropIfExists('dnf_roles');
    }
}
