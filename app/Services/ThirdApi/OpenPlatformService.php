<?php


namespace App\Services\ThirdApi;


use App\Models\Platform;
use EasyWeChat\OpenPlatform\Application;
use Psr\SimpleCache\CacheInterface;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:48
 */
class OpenPlatformService extends Application
{
    public function __construct(Platform $platform)
    {
        $config = [
            'app_id' => $platform->app_id,
            'secret' => $platform->app_secret,
            'token' => $platform->token,
            'aes_key' => $platform->aes_key
        ];
        parent::__construct($config);
        $cache = app(CacheInterface::class);
        $this->rebind('cache', $cache);
    }
}
