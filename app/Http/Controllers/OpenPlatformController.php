<?php


namespace App\Http\Controllers;


use App\Exceptions\BusinessExceptions\UnavailableException;
use App\Models\Platform;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OpenPlatform\Application;
use Illuminate\Http\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
        return (new OpenPlatformService($this->openPlatformModel))->getApplication();
    }

    /**
     * 获取调用接口核心参数 component_access_token
     * @return array
     * @throws \Throwable
     */
    public function getSecretConfig(): array
    {
        $openPlatform = $this->getOpenPlatform();
        $error = null;
        $token = $openPlatform->access_token->getToken();
        return [
            'name' => $this->openPlatformModel->name,
            'app_id' => $this->openPlatformModel->app_id,
            'serve_url' => route('openPlatformServe', ['openPlatformSlug' => $this->openPlatformModel->slug]),
            'bind_url' => route('bind', ['openPlatformSlug' => $this->openPlatformModel->slug]),
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAuthorizer()
    {
        $getCache = request()->query('getCache');
        if ($getCache) {
            return Cache::store('file')->get('authorizer');
        }
        $openPlatform = $this->getOpenPlatform();
        $appId = \request()->route('appId');
        return $openPlatform->getAuthorizer($appId);
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

    /**
     * 获取可信任域名
     *
     * @throws \Throwable
     */
    public function getServerDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_server_domain',
            ['action' => 'get']);
    }

    /**
     * 删除可信任域名
     *
     * @throws \Throwable
     */
    public function deleteServerDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_server_domain',
            ['action' => 'delete', 'wxa_server_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }

    /**
     * 添加可信任域名
     *
     * @throws \Throwable
     */
    public function addServerDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_server_domain',
            ['action' => 'add', 'wxa_server_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }

    /**
     * 修改可信任域名
     *
     * @throws \Throwable
     */
    public function setServerDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_server_domain',
            ['action' => 'set', 'wxa_server_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }
    /**
     * 下载域名校验文件
     *
     * @throws \Throwable
     */
    public function getDomainConfirmFile()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/component/get_domain_confirmfile');
    }

    /**
     * 获取可信任业务域名
     *
     * @throws \Throwable
     */
    public function getWebDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => 'get']);
    }

    /**
     * 删除可信任域名
     *
     * @throws \Throwable
     */
    public function deleteWebDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => 'delete', 'wxa_jump_h5_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }

    /**
     * 添加可信任域名
     *
     * @throws \Throwable
     */
    public function addWebDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => 'add', 'wxa_jump_h5_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }

    /**
     * 修改可信任域名
     *
     * @throws \Throwable
     */
    public function setWebDomain()
    {
        $openPlatform = $this->getOpenPlatform();
        $domainList = request()->input('domainList');
        $isPublishedTogether = request()->input('isPublishedTogether', false);
        return $openPlatform->component->httpPostJson('cgi-bin/component/modify_wxa_jump_domain',
            ['action' => 'set', 'wxa_jump_h5_domain' => $domainList, 'is_modify_published_together' => $isPublishedTogether]);
    }

    /**
     * 获取代码草稿
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function codeDrafts()
    {
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->getDrafts();
    }

    /**
     * 将草稿添加为模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addCodeDraftToTemplate()
    {
        $type = request('type');
        $draftId = request('draftId');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->createFromDraft($draftId, $type);
    }

    /**
     * 获取代码模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function codeTemplate()
    {
        $type = request('type');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->list($type);
    }

    /**
     * 删除代码模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteCodeTemplate()
    {
        $templateId = request('templateId');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->delete($templateId);
    }
}
