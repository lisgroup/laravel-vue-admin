<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('线路名')->index();
            $table->string('price', 32)->default('')->comment('票价');
            $table->enum('car_type', ['大巴', '中巴', '地铁'])->default('大巴')->comment('车型');
            $table->string('depart_time', 64)->default('')->comment('发车间隔');
            $table->string('open_time', 255)->default('')->comment('营运时间');
            $table->string('total_time', 32)->default('')->comment('全程时间');
            $table->string('via_road', 255)->default('')->comment('途经道路');
            $table->string('company', 64)->default('')->comment('公交公司');
            // 站点考虑使用 elasticsearch 全文索引
            $table->text('station')->comment('途经站点(去程)');
            $table->text('station_back')->comment('途经站点(返程)');
            $table->string('reason', 255)->default('')->comment('编辑理由');
            $table->string('username', 64)->default('')->comment('网名');
            $table->tinyInteger('is_show')->default('0')->comment('是否审核');
            $table->date('last_update')->comment('最后更新日期');
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
        Schema::dropIfExists('lines');
    }
}
