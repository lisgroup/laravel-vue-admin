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
