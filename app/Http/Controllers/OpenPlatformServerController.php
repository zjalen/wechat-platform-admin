<?php


namespace App\Http\Controllers;


use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OpenPlatform\Server\Guard;
use Exception;
use Illuminate\Support\Facades\Log;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 13:43
 */
class OpenPlatformServerController extends BaseController
{

    /**
     * 初始化
     * @return OpenPlatformService
     */
    private function getOpenPlatform(): OpenPlatformService
    {
        $openPlatformModel = request()->attributes->get('openPlatform');
        return new OpenPlatformService($openPlatformModel);
    }

    /**
     * 处理微信服务器事件推送
     * !!! 注意开启 csrf 以让微信推送 ticket 等可以顺利通过 post 发送请求
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function serve(): Response
    {
        // 处理事件
        $openPlatform = $this->getOpenPlatform();
        $server = $openPlatform->server;
        // 处理授权成功事件，其他事件同理
        $server->push(function ($message) {
            Log::info($message);
            // $message 为微信推送的通知内容，不同事件不同内容，详看微信官方文档
            // 获取授权公众号 AppId： $message['AuthorizerAppid']
            // 获取 AuthCode：$message['AuthorizationCode']
            // 然后进行业务处理，如存数据库等...
        }, Guard::EVENT_AUTHORIZED);
        $server->push(function ($message) {
            Log::info($message);
        }, Guard::EVENT_UNAUTHORIZED);
        return $server->serve();
    }

    /**
     * 接管微信公众号或小程序用户消息
     * @param string $appId
     * @return string|Response
     */
    public function notify(string $appId)
    {
        try {
            $officialAccount = $this->getOpenPlatform()->officialAccount($appId);
            $openId = null;
            $customContent = null;
            // 这里的 server 为授权方的 server，而不是开放平台的 server，请注意！！！
            $officialAccount->server->push(function ($message) use (&$openId, &$customContent) {
                // TODO 线上调试使用，不需要存储消息可删除 Log 语句
                Log::info($message);
                switch ($message['MsgType']) {
                    case 'event':
                        return '';
                    case 'text':
                        $content = $message['Content'];
                        $contentArr = explode('QUERY_AUTH_CODE:', $content);
                        // !!!全网发布测试 —— 客服消息
                        if (count($contentArr) > 0) {
                            $query_auth_code = $contentArr[1];
                            $openId = $message['FromUserName'];
                            $customContent = $query_auth_code.'_from_api';
                            return '';
                        }
                        switch ($content) {
                            case 'test-send':
                                return 'test-callback';
                            // !!!全网发布测试 —— 消息回复
                            case 'TESTCOMPONENT_MSG_TYPE_TEXT':
                                return 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
                            default:
                                return '';
                        }
                    case 'image':
                        return '收到图片消息';
                    case 'voice':
                        return '收到语音消息';
                    case 'video':
                        return '收到视频消息';
                    case 'location':
                        return '收到坐标消息';
                    case 'link':
                        return '收到链接消息';
                    // ... 其它消息
                    default:
                        return '收到其它消息';
                }
            });
            if ($openId && $customContent) {
                // !!!全网发布测试 —— 客服消息
                $this->sendCustomMessage($officialAccount, $openId, $customContent);
            }
            return $officialAccount->server->serve();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    /**
     * 发送客服消息
     * @param  \EasyWeChat\OfficialAccount\Application  $officialAccount
     * @param  string  $openId
     * @param  string  $content
     */
    private function sendCustomMessage(
        \EasyWeChat\OfficialAccount\Application $officialAccount,
        string $openId,
        string $content
    ) {
        try {
            $officialAccount->customer_service->message(new Text($content))->to($openId)->send();
        } catch (InvalidArgumentException | InvalidConfigException | RuntimeException $e) {
            Log::error($e);
        }
    }
}
