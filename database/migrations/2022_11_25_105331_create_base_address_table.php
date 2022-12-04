<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('基址名称');
            $table->string('const_name')->default('')->comment('常量名');
            $table->bigInteger('address')->comment('十进制数值');
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
        Schema::dropIfExists('base_address');
    }
}
