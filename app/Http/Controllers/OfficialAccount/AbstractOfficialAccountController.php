<?php


namespace App\Http\Controllers\OfficialAccount;


use App\Models\Platform;
use EasyWeChat\OfficialAccount\Application;
use Psr\SimpleCache\CacheInterface;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/4/25 14:17
 */
abstract class AbstractOfficialAccountController
{
    /**
     * @var Platform 经过中间件过滤后的公众平台模型
     */
    protected $officialAccountModel;

    /**
     * 获取已授权公众号实例
     *
     */
    protected function getOfficialAccount(): Application
    {
        /** @var Platform $platform */
        $platform = request()->attributes->get('officialAccountModel');
        $this->officialAccountModel = $platform;
        $config = [
            'app_id' => $platform->app_id,
            'secret' => $platform->app_secret,
            'token' => $platform->token,
            'aes_key' => $platform->aes_key
        ];
        $app = new Application($config);
        $cache = app(CacheInterface::class);
        $app->rebind('cache', $cache);
        return $app;
    }
}
