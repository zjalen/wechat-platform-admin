<?php

namespace App\Http\Controllers\OpenPlatform;

class BetaMiniProgramController extends AbstractOpenPlatformController
{
    /**
     * 创建试用小程序
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store()
    {
        $openPlatform = $this->getOpenPlatform();
        $name = request()->input('name');
        $openid = request()->input('openid');
        return $openPlatform->component->httpPostJson('wxa/component/fastregisterbetaweapp',
            ['name' => $name, 'openid' => $openid]);
    }
}
