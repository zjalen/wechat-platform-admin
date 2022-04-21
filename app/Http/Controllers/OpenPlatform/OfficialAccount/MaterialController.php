<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class MaterialController extends AbstractOpenPlatformController
{

    /**
     * 获取永久素材列表
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws ParamsErrorException
     * @throws WeChatException
     */
    public function index()
    {
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice', 'news'])) {
            throw new ParamsErrorException();
        }
        $offset = request('offset', 0);
        $count = request('count', 20);
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->list($type, $offset, $count);
    }

    /**
     * 上传永久文件素材
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws ParamsErrorException
     * @throws WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function store()
    {
        $fileName = request()->input('fileName');
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice', 'thumb'])) {
            throw new ParamsErrorException();
        }
        $officialAccount = $this->getOfficialAccount();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->appId, $fileName, $type);
        if ($type == 'video') {
            $title = request()->input('title');
            $description = request()->input('description');
            return $officialAccount->material->uploadVideo($file, $title, $description);
        }
        return $officialAccount->material->upload($type, $file);
    }

    /**
     * 查看某个素材详情
     *
     * @return mixed
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function show()
    {
        $mediaId = request()->route('material');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->get($mediaId);
    }

    /**
     * 删除永久素材
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function destroy()
    {
        $mediaId = request('media_id');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->delete($mediaId);
    }

    /**
     * 获取永久素材总数
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function count()
    {
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/material/get_materialcount');
    }
}
