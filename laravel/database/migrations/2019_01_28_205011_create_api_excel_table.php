<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiExcelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    $this->down();
		Schema::create('api_excel', function(Blueprint $table)
		{
			$table->increments('id')->comment('主键 id');
			$table->string('appkey')->default('')->comment('appkey');
            $table->unsignedTinyInteger('concurrent')->default('5')->comment('并发请求数，默认 5');
			$table->unsignedInteger('api_param_id')->default('0')->comment('关联 api_param 表的 id');
			$table->string('upload_url', 255)->default('')->comment('上传地址');
			$table->string('finish_url', 255)->default('')->comment('处理完成 url');
			$table->string('description')->default('')->comment('描述');
			$table->unsignedInteger('uid')->default('0')->comment('上传用户ID号');
			$table->unsignedTinyInteger('state')->default('0')->comment('处理状态，0 新加入，1 开始处理；2 处理完成');
			$table->timestamps();
			$table->index('api_param_id', 'index_api_param_id');
			$table->index('uid', 'index_uid');
			$table->index('state', 'index_state');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('api_excel');
	}

}
