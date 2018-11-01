<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('班次名称')->index();
            $table->string('cid')->default('175ecd8d-c39d-4116-83ff-109b946d7cb4')->comment('cid');
            $table->string('LineGuid')->default('')->comment('guid');
            $table->string('LineInfo')->default('')->comment('班次');
            $table->string('FromTo')->default('')->comment('方向')->index();
            $table->integer('expiration');
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
        Schema::dropIfExists('bus_lines');
    }
}
