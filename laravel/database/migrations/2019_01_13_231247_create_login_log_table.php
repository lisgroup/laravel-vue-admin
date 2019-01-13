<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0)->comment('user_id');
            $table->string('ip')->comment('ip');
            $table->string('login_time')->comment('登录时间');
            $table->string('address')->comment('地理位置');
            $table->string('device')->comment('设备名称');
            $table->string('device_type')->comment('设备类型');
            $table->string('browser')->comment('浏览器');
            $table->string('platform')->comment('操作系统');
            $table->string('language')->comment('语言');
            $table->timestamps();
            // $table->softDeletes(); // 软删除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_log');
    }
}
