<?php


namespace App\Http\Controllers;


use App\Exceptions\BusinessExceptions\UnavailableException;
use App\Models\Platform;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OpenPlatform\Application;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 16:10
 */
class OpenPlatformController extends Controller
{

    /**
     * 中间件验证后获取的公众号数据模型
     * @var Platform
     */
    private $officialAccountModel;

    /**
     * 中间件验证后获取的开放平台数据模型
     * @var Platform
     */
    private $openPlatformModel;

    /**
     * 初始化
     * @return Application
     */
    private function getOpenPlatform(): Application
    {
        $this->openPlatformModel = request()->attributes->get('openPlatform');
        return new OpenPlatformService($this->openPlatformModel);
    }

    /**
     * 获取调用接口核心参数 component_access_token
     * @return array
     */
    public function getSecretConfig(): array
    {
        $openPlatform = $this->getOpenPlatform();
        $error = null;
        try {
            $token = $openPlatform->access_token->getToken();
        } catch (HttpException|InvalidArgumentException|InvalidConfigException|RuntimeException|\Psr\SimpleCache\InvalidArgumentException $e) {
            $token = null;
            $error = $e->getMessage();
        }
        return [
            'name' => $this->openPlatformModel->name,
            'app_id' => $this->openPlatformModel->app_id,
            'serve_url' => route('openPlatformServe', ['opSlug' => $this->openPlatformModel->slug]),
            'bind_url' => route('bind', ['opSlug' => $this->openPlatformModel->slug]),
            'access_token' => $token,
            'errMsg' => $error
        ];
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
            ['opSlug' => $this->openPlatformModel->slug]));
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
     * 获取所有已绑定的公众号/小程序账号列表
     * @throws UnavailableException
     */
    public function getAuthorizerList()
    {
        $limit = request('limit', 20);
        $skip = request('skip', 0);
        $openPlatform = $this->getOpenPlatform();
        try {
            $authorizers = $openPlatform->getAuthorizers($skip, $limit);
        } catch (\Exception $exception) {
            Log::error($exception);
            throw new UnavailableException($exception->getMessage());
        }
        return $authorizers;
    }

    /**
     * 获取已绑定的平台账号详情
     * @return mixed
     * @throws UnavailableException|\Psr\SimpleCache\InvalidArgumentException
     */
    public function getAuthorizer()
    {
        $getCache = request()->query('getCache');
        if ($getCache) {
            return Cache::store('file')->get('authorizer');
        }
        $openPlatform = $this->getOpenPlatform();
        $appId = \request()->route('appId');
        try {
            $authorizer = $openPlatform->getAuthorizer($appId);
        } catch (\Exception $exception) {
            Log::error($exception);
            throw new UnavailableException($exception->getMessage());
        }
        return $authorizer;
    }

    /**
     * 创建试用小程序
     *
     * @throws \Throwable
     */
    public function createBetaMiniProgram()
    {
        $openPlatform = $this->getOpenPlatform();
        $name = request()->input('name');
        $openid = request()->input('openid');
        return $openPlatform->component->httpPostJson('wxa/component/fastregisterbetaweapp',
            ['name' => $name, 'openid' => $openid]);
    }
}
