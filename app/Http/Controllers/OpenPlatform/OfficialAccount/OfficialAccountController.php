<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Models\Authorizer;

class OfficialAccountController extends AbstractOpenPlatformController
{
    /**
     * 获取已授权小程序基本信息
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function show()
    {
        $application = $this->getMiniProgramApplication();
        $appId = request()->route('appId');
        $authorizer = Authorizer::query()->where('app_id', $appId)->first();
        $basicInfo = $application->account->getBasicInfo();
        if ($authorizer && $basicInfo) {
            $basicInfo['is_auto_reply_open'] = $authorizer->is_auto_reply_open;
        }
        return $basicInfo;
    }

    /**
     * 更新自动回复状态
     *
     * @return bool
     */
    public function updateAutoReplyConfig(): bool
    {
        $openFlag = request('is_auto_reply_open');
        $appId = request()->route('appId');
        $authorizer = Authorizer::query()->where('app_id', $appId)->first();
        $authorizer->is_auto_reply_open = $openFlag;
        return $authorizer->save();
    }
}
