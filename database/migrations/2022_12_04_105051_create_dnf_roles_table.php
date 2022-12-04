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
            $table->bigInteger('account');
            $table->bigInteger('role_id');
            $table->string('name')->default('');
            $table->integer('character');
            $table->integer('advancement');
            $table->integer('awakening');
            $table->integer('level');
            $table->integer('prestige');
            $table->integer('position');
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
