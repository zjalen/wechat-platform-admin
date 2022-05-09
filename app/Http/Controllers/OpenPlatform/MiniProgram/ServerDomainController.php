<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class ServerDomainController extends AbstractOpenPlatformController
{
    /**
     * 获取服务器域名
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function index()
    {
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->domain->modify(['action' => 'get']);
    }

    /**
     * 增、删、改改服务器域名
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     * @throws ParamsErrorException
     */
    public function update($opId, $appId, $action)
    {
        $miniProgram = $this->getMiniProgram();
        $params['requestdomain'] = request('request', []);
        $params['wsrequestdomain'] = request('ws', []);
        $params['uploaddomain'] = request('upload', []);
        $params['downloaddomain'] = request('download', []);
        $params['udpdomain'] = request('udp', []);
        $params['tcpdomain'] = request('tcp', []);

        $params['action'] = $action;
        if (!in_array($params['action'], ['add','set','delete'])) {
            throw new ParamsErrorException();
        }
        return $miniProgram->domain->modify($params);
    }
}
