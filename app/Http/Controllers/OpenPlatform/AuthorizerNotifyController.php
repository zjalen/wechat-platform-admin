<?php


namespace App\Http\Controllers\OpenPlatform;


use App\Jobs\SendCustomMessage;
use App\Models\Authorizer;
use App\Models\AutoReplyRule;
use App\Models\Platform;
use EasyWeChat\Kernel\Messages\Media;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Text;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use function request;

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
     */
    public function store()
    {
        $appId = request()->route('appId');
        try {
            $officialAccount = $this->getOfficialAccount($appId);
            // 这里的 server 为授权方的 server，而不是开放平台的 server，请注意！！！
            $officialAccount->server->push(function ($message) use ($appId) {
                // TODO 线上调试使用，不需要存储消息可删除 Log 语句
                Log::info($message);
                switch ($message['MsgType']) {
                    case 'event':
                        $event = $message['Event'];
                        $eventKey = $message['EventKey'];
                        if (strtoupper($event) == 'CLICK') {
                            return $this->getReturnMessage($eventKey, $appId);
                        }
                        return '';
                    case 'text':
                        $content = $message['Content'];
                        // !!!全网发布测试 —— 客服消息
                        if (Str::startsWith($content, 'QUERY_AUTH_CODE:')) {
                            $contentArr = explode('QUERY_AUTH_CODE:', $content);
                            $query_auth_code = $contentArr[1];
                            $openId = $message['FromUserName'];
                            $customContent = $query_auth_code.'_from_api';
                            SendCustomMessage::dispatchAfterResponse($this->openPlatformModel, $appId, $openId, new Text($customContent));
                            return '';
                        }
                        $officialAccountModel = Authorizer::query()->where('app_id', $appId)->first();
                        if (!$officialAccountModel->is_auto_reply_open) {
                            return '';
                        }
                        switch ($content) {
                            case 'openid_test':
                                // 获取 openid
                                return $message['FromUserName'];
                            case 'TESTCOMPONENT_MSG_TYPE_TEXT':
                                // !!!全网发布测试 —— 消息回复
                                return 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
                            default:
                                return $this->getReturnMessage($content, $appId);
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
            return $officialAccount->server->serve();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    private function getReturnMessage($content, $appId)
    {
        $autoReplyRule = AutoReplyRule::query()->where('app_id', $appId)->where('keyword', $content)->where('match_type', 1)->first();
        if (!$autoReplyRule) {
            $autoReplyRule = AutoReplyRule::query()->where('app_id', $appId)->where('keyword','like', '%'.$content.'%')->where('match_type', 0)->first();
        }
        if (!$autoReplyRule){
            return '';
        }
        switch ($autoReplyRule->type) {
            case 0:
            case 1:
                return $autoReplyRule->content['text'];
            case 2:
            case 3:
            case 4:
                return new Media($autoReplyRule->content['media_id']);
            case 5:
                return new NewsItem($autoReplyRule->content);
        }
        return '';
    }
}
