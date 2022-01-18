<?php


namespace App\Http\Controllers\OpenPlatform;


use App\Http\Controllers\Controller;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\OpenPlatform\Application;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/1/17 16:22
 */
abstract class AbstractOpenPlatformController extends Controller
{
    protected $openPlatformModel;

    /**
     * 初始化
     * @return Application
     */
    protected function getOpenPlatform(): Application
    {
        $this->openPlatformModel = request()->attributes->get('openPlatform');
        return (new OpenPlatformService($this->openPlatformModel))->getApplication();
    }

    /**
     * 获取已授权公众号实例
     *
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    protected function getOfficialAccount($appId): \EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
    {
        $openPlatformModel = request()->attributes->get('openPlatform');
        $this->openPlatformModel = request()->attributes->get('openPlatform');
        $openPlatformService = new OpenPlatformService($openPlatformModel);
        // 生成实例，代小程序实现业务
        return $openPlatformService->getOfficialAccountApplication($appId);
    }
}
