<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use Illuminate\Support\Facades\Log;

class OpenAppController extends AbstractOpenPlatformController
{
    /**
     * 创建并绑定第三方平台账号
     * ！！！注意此账号仅可用来获取绑定同一账号下的同一主体的公众号/小程序的用户 unionId，不可用来登录或认证使用
     * @return mixed|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function store()
    {
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->create();
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        Log::info($result);
        return $result;
    }

    /**
     * 将公众号绑定到第三方平台
     * ！！！注意此第三方平台必须是上面代码注册的，不能是通过微信开放平台认证注册的
     * @param  string  $openAppId
     * @return mixed|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function update(string $openAppId)
    {
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->bindTo($openAppId);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        Log::info($result);
        return $result;
    }

    /**
     * 获取所有已绑定的第三方平台
     * @return mixed|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function index()
    {
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->getBinding();
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        Log::info($result);
        return $result;
    }

    /**
     * 将账号从第三方平台解绑
     * @param  string  $openAppId
     * @return mixed|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function destroy(string $openAppId)
    {
        $officialAccount = $this->getOfficialAccount();
        try {
            $result = $officialAccount->account->unbindFrom($openAppId);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        Log::info($result);
        return $result;
    }
}
