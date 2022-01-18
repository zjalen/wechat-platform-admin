<?php

namespace App\Http\Controllers\OpenPlatform;

class AuthorizerController extends AbstractOpenPlatformController
{
    /**
     * 获取已授权账号的 appId
     *
     * @return mixed
     */
    public function index()
    {
        $limit = request('limit', 20);
        $skip = request('skip', 0);
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->getAuthorizers($skip, $limit);
    }

    /**
     * 通过已授权账号 appId 查看详情
     *
     * @param $opId
     * @param $appId
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function show($opId, $appId)
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->getAuthorizer($appId);
    }
}
