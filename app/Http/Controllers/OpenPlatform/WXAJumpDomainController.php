<?php

namespace App\Http\Controllers\OpenPlatform;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use Illuminate\Http\Request;

class WXAJumpDomainController extends AbstractOpenPlatformController
{
    /**
     * 获取业务域名信息
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => 'get']);
    }

    /**
     * 增、删、改 业务域名
     *
     * @param  Request  $request
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ParamsErrorException
     */
    public function update(Request $request)
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = $request->input('domainList');
        $action = $request->input('action');
        if (!in_array($action, ['add','set','delete'])) {
            throw new ParamsErrorException();
        }
        $isPublishedTogether = $request->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => $action, 'wxa_jump_h5_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }
}
