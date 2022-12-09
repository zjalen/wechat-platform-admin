<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class OrderPathController extends AbstractOpenPlatformController
{
    /**
     * 查询订单页 Path 信息
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $infoType = request('infoType') ?: 0;
        $data = ['info_type' => $infoType];
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->httpPostJson('wxa/security/getorderpathinfo', $data);
    }


    /**
     * 设置小程序订单 path
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store()
    {
        $params['path'] = request('path');
        $params['video'] = request('video');
        $params['img_list'] = request('img_list') ? explode(',', request('img_list')) : null;
        $params['test_account'] = request('test_account');
        $params['test_pwd'] = request('test_pwd');
        $params['test_remark'] = request('test_remark');
        $params['appid_list'] = request('appid_list') ? explode(',', request('appid_list')) : null;


        $data = ['batch_req' => $params];
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->httpPostJson('wxa/security/applysetorderpathinfo', $data);
    }
}
