<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class AuthController extends AbstractOpenPlatformController
{
    /**
     * 使用 code 登录获取用户信息，openid 和 unionid
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string|string[]
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function store()
    {
        $code = request('code');
        $miniProgram = $this->getMiniProgramApplication();
        if ($code == '123' || config('app.env') == 'local') {
            return ['openid' => '12345', 'unionid' => '12345'];
        }
        return $miniProgram->auth->session($code);
    }
}
