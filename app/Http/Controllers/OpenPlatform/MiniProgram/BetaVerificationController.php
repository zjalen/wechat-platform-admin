<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class BetaVerificationController extends AbstractOpenPlatformController
{
    /**
     * 测试小程序转正
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store()
    {
        $miniProgram = $this->getMiniProgram();
        $verifyInfo = [
            'enterprise_name' => request('enterprise_name'),
            'code' => request('code'),
            'code_type' => request('code_type'),
            'legal_persona_wechat' => request('legal_persona_wechat'),
            'legal_persona_name' => request('legal_persona_name'),
            'component_phone' => request('component_phone'),
            'legal_persona_idcard' => request('legal_persona_idcard'),
        ];
        return $miniProgram->account->httpPostJson('wxa/verifybetaweapp',
            ['verify_info' => $verifyInfo]);
    }
}
