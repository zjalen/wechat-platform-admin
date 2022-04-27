<?php

namespace App\Http\Controllers\OfficialAccount;

use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class DraftController extends AbstractOfficialAccountController
{
    /**
     * 获取草稿列表
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function index()
    {
        $offset = request('offset', 0);
        $count = request('count', 20);
        $no_content = request('no_content', 0);
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/draft/batchget',
            ['offset' => $offset, 'count' => $count, 'no_content' => $no_content]);
    }

    /**
     * 创建草稿
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function store()
    {
        $params = request()->input();
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/draft/add', $params);
    }

    /**
     * 修改草稿
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function update()
    {
        $params = request()->input();
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/draft/update', $params);
    }

    /**
     * 获取草稿
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function show()
    {
        $mediaId = request()->route('draft');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/draft/get', ['media_id' => $mediaId]);
    }

    /**
     * 删除草稿
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function destroy()
    {
        $mediaId = request()->route('draft');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/draft/delete', ['media_id' => $mediaId]);
    }
}
