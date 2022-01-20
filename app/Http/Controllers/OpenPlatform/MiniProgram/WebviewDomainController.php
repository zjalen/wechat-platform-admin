<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class WebviewDomainController extends AbstractOpenPlatformController
{
    /**
     * 查询业务域名
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function index()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->domain->setWebviewDomain([], 'get');
    }

    /**
     * 增、删、改、同步业务域名
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     * @throws ParamsErrorException
     */
    public function update($opId, $appId, $action)
    {
        $miniProgram = $this->getMiniProgramApplication();
        $domainArray = request('webDomain', []);
        if (!in_array($action, ['add', 'set', 'delete', 'sync'])) {
            throw new ParamsErrorException();
        }
        $act = $action === 'sync' ? null : $action;
        return $miniProgram->domain->setWebviewDomain($domainArray, $act);
    }
}
