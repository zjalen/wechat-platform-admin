<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoReplyRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_reply_rules', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->comment('appid');
            $table->string('name')->comment('规则名');
            $table->string('keyword')->comment('关键词');
            $table->tinyInteger('match_type')->comment('匹配方式');
            $table->tinyInteger('type')->comment('回复类型');
            $table->json('content')->comment('回复内容');
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
        Schema::dropIfExists('auto_reply_rules');
    }
}
