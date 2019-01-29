<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiParamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    $this->down();
		Schema::create('api_param', function(Blueprint $table)
		{
			$table->increments('id')->comment('主键 id');
			$table->string('website')->default('')->comment('网址');
			$table->string('name')->default('')->comment('接口名称');
			$table->enum('method', ['get', 'post', 'other'])->default('get')->comment('请求方式');
			$table->string('url')->default('')->comment('接口地址');
			$table->string('param')->default('')->comment('参数');
			$table->unsignedTinyInteger('state')->default('1')->comment('是否启用，0 未启用，1 启用');
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
		Schema::dropIfExists('api_param');
	}

}
