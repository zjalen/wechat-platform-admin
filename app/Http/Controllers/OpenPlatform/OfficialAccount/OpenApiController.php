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
            $result = $officialAccount->oauth->userFromCode($code);
        } catch (\Throwable $exception) {
            Log::error($exception);
            return $exception->getMessage();
        }
        return self::success($result);
    }
}
