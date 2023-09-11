<?php


namespace App\Http\Controllers\OpenPlatform\OfficialAccount;


use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Http\OpenApiResponse;
use Illuminate\Support\Facades\Log;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/5/9 11:42
 */
class OpenApiController extends AbstractOpenPlatformController
{
    use OpenApiResponse;

    /**
     * 公众号 code 换取用户信息
     *
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function getUserInfo()
    {
        $code = request()->input('code');
        if ($code == '123' || config('app.env') == 'local') {
            return self::success(['openid' => '12345', 'unionid' => '12345']);
        }
        $officialAccount = $this->getOfficialAccount();
        try {
            $res = $officialAccount->oauth->userFromCode($code);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return self::success($res);
    }

    /**
     * 获取 jssdk config
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getJsSdk(): \Illuminate\Http\JsonResponse
    {
        $url = request()->input('url');
        $apiList = request()->input('api_list', []);
        $debug = request()->input('debug', false);
        $beta = request()->input('beta', false);
        $json = request()->input('json', false);
        $openTagList = request()->input('open_tag_list', []);
        $officialAccount = $this->getOfficialAccount();
        $res = $officialAccount->jssdk->buildConfig($apiList, $debug, $beta, $json, $openTagList, $url);
        if (array_key_exists('errcode', $res) && $res['errcode'] != 0) {
            Log::error($res);
            return self::fail($res);
        }
        return self::success($res);
    }

    /**
     * 发送模板消息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function sendTemplateMessage(): \Illuminate\Http\JsonResponse
    {

        $templateId = request()->input('template_id');
        $openid = request()->input('openid');
        $data = request()->input('data');
        $url = request()->input('url');
        $miniProgram = request()->input('miniprogram');

        $params = [
            'touser' => $openid,
            'template_id' => $templateId,
            'url' => $url,
            'data' => json_decode($data, true),
            'miniprogram' => $miniProgram,

        ];

        $officialAccount = $this->getOfficialAccount();
        $res = $officialAccount->template_message->send($params);
        if (array_key_exists('errcode', $res) && $res['errcode'] != 0) {
            Log::error($res);
            return self::fail($res);
        }
        return self::success($res);
    }
}
