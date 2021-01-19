<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApiParamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('api_param', function(Blueprint $table) {
            $table->tinyInteger('is_show_customer', false, true)->default(1)->after('state')->comment('Customer用户是否展示');
            $table->smallInteger('sort_index', false, true)->default(0)->after('state')->comment('排序，大数靠前');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    // down
	}

}
