<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class SignatureController extends AbstractOpenPlatformController
{
    /**
     * 设置简介
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     * @throws WeChatException
     */
    public function update()
    {
        $miniProgram = $this->getMiniProgram();
        $signature = request()->input('signature');
        return $miniProgram->account->updateSignature($signature);
    }
}
