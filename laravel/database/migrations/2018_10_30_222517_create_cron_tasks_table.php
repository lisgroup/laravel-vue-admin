<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cid')->default('')->comment('cid');
            $table->string('LineGuid')->default('')->comment('guid');
            $table->string('LineInfo')->default('')->comment('班次');
            $table->tinyInteger('is_task')->default('0')->comment('是否启动：1 启动，0 关闭');
            $table->time('start_at')->comment('开始时间');
            $table->time('end_at')->comment('结束时间');
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
        Schema::dropIfExists('cron_tasks');
    }
}
