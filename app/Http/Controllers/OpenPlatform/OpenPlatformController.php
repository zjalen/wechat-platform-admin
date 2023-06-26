<?php


namespace App\Http\Controllers\OpenPlatform;


use EasyWeChat\Kernel\Exceptions\RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/1/14 18:28
 */
class OpenPlatformController extends AbstractOpenPlatformController
{
    /**
     * 获取基本核心参数
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function show(): array
    {
        $openPlatform = $this->getOpenPlatform();
        $error = null;
        try {
            $token = $openPlatform->access_token->getToken();
        } catch (\Throwable $e) {
            Log::error($e);
            $token = null;
            $error = $e->getMessage();
        }
        return [
            'name' => $this->openPlatformModel->name,
            'app_id' => $this->openPlatformModel->app_id,
            'token' => $this->openPlatformModel->token,
            'aes_key' => $this->openPlatformModel->aes_key,
            'domain' => config('app.url'),
            'serve_url' => route('openPlatformNotify', ['openPlatformSlug' => $this->openPlatformModel->slug]),
            'notify_url' => urldecode(route('authorizerNotify', ['openPlatformSlug' => $this->openPlatformModel->slug, 'appId' => '$APPID$'])),
            'bind_url' => route('bind', ['openPlatformSlug' => $this->openPlatformModel->slug]),
            'fast_create_mp_url' => route('fastCreateMpAuth', ['openPlatformSlug' => $this->openPlatformModel->slug]),
            'access_token' => $token,
            'errMsg' => $error
        ];
    }

    /**
     * 下载 web 域名校验文件
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function webDomainValidateFile()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/component/get_domain_confirmfile');
    }


    /**
     * 绑定公众平台|小程序到开放平台页面
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws RuntimeException
     */
    public function bind()
    {
        $openPlatform = $this->getOpenPlatform();
        $authUrl = $openPlatform->getPreAuthorizationUrl(route('bindCallback',
            ['openPlatformSlug' => $this->openPlatformModel->slug]));
        return view('authorize', ['authUrl' => $authUrl]);
    }

    /**
     * 开放平台绑定公众号|小程序回调页面
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function bindCallback()
    {
        $authCode = request()->input('auth_code');
        $openPlatform = $this->getOpenPlatform();
        // handle 触发绑定成功事件
        $openPlatform->handleAuthorize($authCode);
        return view('authorized');
    }

    /**
     * 复用公众号认证信息快速注册小程序授权页面
     *
     * @return View
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function fastCreateMpAuth(): View
    {
        $appId = request()->query('appId');
        $slug = request()->route('openPlatformSlug');
        $callbackUrl = route('fastCreateMiniProgramAuthCallback', ['openPlatformSlug' => $slug, 'appId' => $appId]);
        $fastRegistrationUrl = $this->getOfficialAccount($appId)->account->getFastRegistrationUrl($callbackUrl);
        return view('authorize', ['authUrl' => $fastRegistrationUrl]);
    }

    /**
     * 开放平台复用公众号认证信息快速注册小程序授权回调
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException|\App\Exceptions\BusinessExceptions\WeChatException
     */
    public function fastCreateMiniProgramAuthCallback()
    {
        $appId = request()->route('appId');
        $ticket = request()->input('ticket');
        Log::info($ticket);
        $officialAccount = $this->getOfficialAccount($appId);
        // handle 触发绑定成功事件
        return $officialAccount->account->httpPostJson('cgi-bin/account/fastregister', ['ticket' => $ticket]);
    }

    /**
     * 查询 rid 的详细信息
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ridInfo()
    {
        $rid = request()->input('rid');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/openapi/rid/get', ['rid' => $rid]);
    }
}
