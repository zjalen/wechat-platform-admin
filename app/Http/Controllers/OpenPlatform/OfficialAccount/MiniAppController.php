<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use Illuminate\Support\Facades\Log;

class MiniAppController extends AbstractOpenPlatformController
{
    /**
     * 公众号绑定小程序
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function store()
    {
        $appid = request()->input('appid');
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->httpPostJson('cgi-bin/wxopen/wxamplink', ['appid' => $appid,'notify_users'=>0,'show_profile' => 0]);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return $result;
    }

    /**
     * 获取所有已绑定的小程序
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function index()
    {
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->httpPostJson('cgi-bin/wxopen/wxamplinkget');
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return $result;
    }

    /**
     * 解绑小程序
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws WeChatException
     */
    public function destroy()
    {
        $miniAppId = request()->route()->parameter('mini_app');
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->httpPostJson('cgi-bin/wxopen/wxampunlink', ['appid' => $miniAppId]);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return $result;
    }
}
