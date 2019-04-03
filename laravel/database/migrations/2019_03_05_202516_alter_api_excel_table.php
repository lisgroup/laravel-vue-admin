<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApiExcelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_excel', function(Blueprint $table) {
            $table->integer('total_excel', false, true)->default(0)->after('state')->comment('计算任务总行数');
            $table->unsignedDecimal('auto_delete', 10, 3)->default(1)->after('state');
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
        //
    }
}
