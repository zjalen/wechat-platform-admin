<?php


namespace App\Http\Controllers\OpenPlatform;


use App\Models\Authorizer;
use App\Services\ThirdApi\OfficialAccountService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:43
 */
class AuthorizerNotifyController extends AbstractOpenPlatformController
{
    /**
     * 接管微信公众号或小程序用户消息
     *
     * @return string|Response
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function store()
    {
        $officialAccount = $this->getOfficialAccount();
        $officialAccountService = new OfficialAccountService();
        $officialAccountModel = Authorizer::query()->where('app_id', $this->appId)->first();
        return $officialAccountService->notifyServe($officialAccount, $officialAccountModel);
    }
}
