<?php


namespace App\Http\Controllers\OfficialAccount;


use App\Services\ThirdApi\OfficialAccountService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:43
 */
class NotifyController extends AbstractOfficialAccountController
{
    /**
     * 处理微信服务器事件推送
     *
     * @return Response
     */
    public function store(): Response
    {
        $officialAccount = $this->getOfficialAccount();
        $officialAccountService = new OfficialAccountService();
        return $officialAccountService->notifyServe($officialAccount, $this->officialAccountModel);
    }
}
