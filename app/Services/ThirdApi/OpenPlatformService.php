<?php


namespace App\Services\ThirdApi;


use App\Exceptions\BusinessExceptions\UnknownException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Models\Platform;
use EasyWeChat\OpenPlatform\Application;
use phpDocumentor\Reflection\Types\This;
use Psr\SimpleCache\CacheInterface;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:48
 */
class OpenPlatformService
{
    /**
     * @var Application
     */
    private $openPlatformApplication;

    public function __construct(Platform $platform)
    {
        $config = [
            'app_id' => $platform->app_id,
            'secret' => $platform->app_secret,
            'token' => $platform->token,
            'aes_key' => $platform->aes_key
        ];
        $app = new Application($config);
        $cache = app(CacheInterface::class);
        $app->rebind('cache', $cache);
        $this->openPlatformApplication = $app;
        return $app;
    }

    public function getApplication(): Application
    {
        return $this->openPlatformApplication;
    }

    /**
     * @throws WeChatException
     */
    public function getAuthorizer($appId)
    {
        $result = $this->openPlatformApplication->getAuthorizer($appId);
        if (!array_key_exists('authorization_info', $result)) {
            if ($result['errcode'] != 0) {
                throw new WeChatException($result['errmsg'], $result['errcode']);
            }
        }
        return $result;
    }

    /**
     * @throws WeChatException
     */
    public function getMiniProgramApplication($appId): \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
    {
        $result = $this->getAuthorizer($appId);
        $refreshToken = $result['authorization_info']['authorizer_refresh_token'];
        // 生成实例，代小程序实现业务
        return $this->openPlatformApplication->miniProgram($appId, $refreshToken);
    }

    /**
     * @throws WeChatException
     */
    public function getOfficialAccountApplication($appId): \EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
    {
        $result = $this->getAuthorizer($appId);
        $refreshToken = $result['authorization_info']['authorizer_refresh_token'];
        // 生成实例，代公众号实现业务
        return $this->openPlatformApplication->officialAccount($appId, $refreshToken);
    }
}
