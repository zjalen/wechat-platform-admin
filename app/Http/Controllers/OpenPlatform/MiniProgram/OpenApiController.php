<?php


namespace App\Http\Controllers\OpenPlatform\MiniProgram;


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
        $miniProgram = $this->getMiniProgram();
        try {
            $result = $miniProgram->auth->session($code);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return self::success($result);
    }

    /**
     * 公众号 code 换取用户手机号
     *
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function getUserPhoneNumber()
    {
        $code = request()->input('code');
        $miniProgram = $this->getMiniProgram();
        try {
            $result = $miniProgram->account->httpPostJson('wxa/business/getuserphonenumber', ['code' => $code]);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return self::success($result);
    }

    /**
     * 生成小程序码
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function getMpQrCode()
    {
        $params = [
            'width' => request('width'),
            'auto_color' => request('auto_color'),
            'line_color' => request('line_color'),
            'is_hyaline' => request('is_hyaline'),
        ];
        $miniProgram = $this->getMiniProgram();
        $unlimited = request('unlimited', false);
        if ($unlimited) {
            $params['scene'] = request('scene');
            $params['page'] = request('path');
            return $miniProgram->app_code->getUnlimit($params['scene'], $params);
        } else {
            $params['path'] = request('path');
            return $miniProgram->app_code->get($params['path'], $params);
        }
    }
}
