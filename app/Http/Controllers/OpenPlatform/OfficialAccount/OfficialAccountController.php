<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Models\Authorizer;

class OfficialAccountController extends AbstractOpenPlatformController
{
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
