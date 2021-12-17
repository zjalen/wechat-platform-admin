<?php


namespace App\Http\Controllers;


use App\Models\Platform;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Support\Collection;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

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
     * @return OpenPlatformService
     */
    private function getOpenPlatform(): OpenPlatformService
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
        } catch (HttpException | InvalidArgumentException | InvalidConfigException | RuntimeException | \Psr\SimpleCache\InvalidArgumentException $e) {
            $token = null;
            $error = $e->getMessage();
        }
        return [
            'name' => $this->openPlatformModel->name,
            'app_id' => $this->openPlatformModel->app_id,
            'serve_url' => route('openPlatformServe', ['opSlug' => $this->openPlatformModel->slug]),
            'access_token' => $token,
            'errMsg' => $error
        ];
    }

    /**
     * 获取所有已绑定的公众号/小程序账号列表
     */
    public function getAuthorizerList()
    {
        $limit = request('limit', 20);
        $skip = request('skip', 0);
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->getAuthorizers($skip, $limit);
    }

    /**
     * 获取已绑定的平台账号详情
     * @return mixed
     */
    public function getAuthorizer()
    {
        $openPlatform = $this->getOpenPlatform();
        $appId = \request()->route('appId');
        return $openPlatform->getAuthorizer($appId);
    }
}
