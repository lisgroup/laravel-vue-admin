<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiExcelLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_excel_logs', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('api_excel_id')->default('0')->comment('关联 api_excel 表的 id');
            $table->unsignedInteger('sort_index')->default('0')->comment('本次查询的排序');
            $table->text('param')->comment('请求参数');
            $table->text('result')->comment('响应结果');
            $table->timestamp('created_at')->nullable();
            $table->index('api_excel_id', 'index_api_excel_id');
            $table->index('sort_index', 'index_sort_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_excel_logs');
    }
}
