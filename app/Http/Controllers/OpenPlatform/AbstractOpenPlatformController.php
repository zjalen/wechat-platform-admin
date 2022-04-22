<?php


namespace App\Http\Controllers\OpenPlatform;


use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\OpenPlatform\Application;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/1/17 16:22
 */
abstract class AbstractOpenPlatformController extends Controller
{
    /**
     * @var Platform 经过中间件过滤后的开放平台模型
     */
    protected $openPlatformModel;

    /**
     * @var string 授权过的平台 appId
     */
    protected $appId;

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
    protected function getOfficialAccount($appId = null): \EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
    {
        if (!$appId) {
            $appId = request()->route('appId');
        }
        $this->appId = $appId;
        $openPlatformModel = request()->attributes->get('openPlatform');
        $openPlatformService = new OpenPlatformService($openPlatformModel);
        // 生成实例，代小程序实现业务
        return $openPlatformService->getOfficialAccountApplication($appId);
    }

    /**
     * 获取小程序实例
     *
     * @param  null  $appId
     * @return \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    protected function getMiniProgramApplication($appId = null): \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
    {
        if (!$appId) {
            $appId = request()->route('appId');
        }
        $this->appId = $appId;
        $openPlatformModel = request()->attributes->get('openPlatform');
        $openPlatformService = new OpenPlatformService($openPlatformModel);
        // 生成实例，代小程序实现业务
        return $openPlatformService->getMiniProgramApplication($this->appId);
    }
}
