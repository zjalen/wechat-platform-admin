<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('名称');
            $table->string('slug')->unique()->comment('标识');
            $table->string('logo')->nullable(true)->comment('logo');
            $table->string('app_id')->unique()->comment('app_id');
            $table->string('app_secret')->comment('密钥');
            $table->string('token')->comment('校验 token');
            $table->string('aes_key')->comment('解密 key');
            $table->string('description')->nullable(true)->comment('简介');
            $table->boolean('is_open')->default(true)->comment('是否开放');
            $table->tinyInteger('type')->default(0)->comment('类型');
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
        Schema::dropIfExists('platforms');
    }
}
