<?php


namespace App\Http\Controllers\OpenPlatform;


use App\Events\SubPlatformAuthorized;
use App\Events\SubPlatformUnAuthorized;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\OpenPlatform\Server\Guard;
use Illuminate\Support\Facades\Log;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use function event;
use function request;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:43
 */
class NotifyController extends AbstractOpenPlatformController
{
    /**
     * 处理微信服务器事件推送
     *
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function store(): Response
    {
        // 处理事件
        $openPlatform = $this->getOpenPlatform();
        $server = $openPlatform->server;
        $openPlatformModel = $this->openPlatformModel;
        // 处理授权成功事件，其他事件同理
        $server->push(function ($message) use ($openPlatformModel) {
            Log::info($message);
            // $message 为微信推送的通知内容，不同事件不同内容，详看微信官方文档
            $appId = $message['AuthorizerAppid'];
            event(new SubPlatformAuthorized($openPlatformModel, $appId));
        }, Guard::EVENT_AUTHORIZED);
        $server->push(function ($message) {
            Log::info($message);
            $openPlatformModel = request()->attributes->get('openPlatform');
            $appId = $message['AuthorizerAppid'];
            event(new SubPlatformUnAuthorized($openPlatformModel, $appId));
        }, Guard::EVENT_UNAUTHORIZED);
        return $server->serve();
    }
}
