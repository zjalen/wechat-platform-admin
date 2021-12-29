<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->comment('操作者 id');
            $table->string('path')->comment('请求路径');
            $table->string('method')->comment('请求方法');
            $table->string('ip')->nullable()->comment('ip');
            $table->json('params')->nullable()->comment('请求参数');
            $table->json('wx_result')->nullable()->comment('微信回复的结果');
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
        Schema::dropIfExists('operation_logs');
    }
}
