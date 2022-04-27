<?php

namespace App\Http\Controllers\OfficialAccount;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class MaterialController extends AbstractOfficialAccountController
{

    /**
     * 获取永久素材列表
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws ParamsErrorException
     */
    public function index()
    {
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice'])) {
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
        $file = $mediaService->getFilePath($this->officialAccountModel->app_id, $fileName, $type);
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
     */
    public function destroy()
    {
        $mediaId = request()->route('material');
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->delete($mediaId);
    }

    /**
     * 获取永久素材总数
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function count()
    {
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->material->httpPostJson('cgi-bin/material/get_materialcount');
    }
}
