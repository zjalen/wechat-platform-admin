<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('nick_name')->unique()->comment('昵称');
            $table->string('slug')->unique()->index()->comment('索引标识');
            $table->string('head_img')->nullable(true)->comment('头像');
            $table->string('app_id')->index()->comment('app_id');
            $table->string('principal_name')->comment('主体名称');
            $table->string('qrcode_url')->comment('二维码地址');
            $table->string('user_name')->comment('原始 id');
            $table->tinyInteger('service_type_info')->default(0)->comment('平台类型');
            $table->tinyInteger('is_mini_program')->default(0)->comment('是否是小程序');
            $table->tinyInteger('status')->default(0)->comment('绑定状态');
            $table->integer('platform_id')->comment('关联第三方平台 id');
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
        Schema::dropIfExists('sub_platforms');
    }
}
