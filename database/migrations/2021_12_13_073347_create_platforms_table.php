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
            $table->string('name')->comment('名称');
            $table->string('slug')->unique()->index()->comment('索引标识');
            $table->string('logo')->nullable(true)->comment('logo');
            $table->string('app_id')->unique()->index()->comment('app_id');
            $table->string('app_secret')->comment('密钥');
            $table->string('token')->comment('校验 token');
            $table->string('aes_key')->comment('解密 key');
            $table->string('description')->nullable(true)->comment('简介');
            $table->boolean('is_open')->default(true)->comment('是否开放');
            $table->boolean('is_auto_reply_open')->default(false)->comment('是否启用自动回复');
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
