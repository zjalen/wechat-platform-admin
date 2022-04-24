<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class ArticleController extends AbstractOpenPlatformController
{
    /**
     * 获取文章列表
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function index()
    {
        $offset = request('offset', 0);
        $count = request('count', 20);
        $no_content = request('no_content', 0);
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/freepublish/batchget',
            ['offset' => $offset, 'count' => $count, 'no_content' => $no_content]);
    }

    /**
     * 创建文章
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function store()
    {
        $articleId = request()->input('media_id');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/freepublish/submit', ['media_id' => $articleId]);
    }

    /**
     * 获取文章
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function show()
    {
        $articleId = request()->route('article');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/freepublish/get', ['article_id' => $articleId]);
    }

    /**
     * 删除文章
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function destroy()
    {
        $articleId = request()->route('article');
        $index = request()->route('index');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/freepublish/delete', ['article_id' => $articleId, 'index' => $index]);
    }
}
