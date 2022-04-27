<?php

namespace App\Http\Controllers\OfficialAccount;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class TemporaryMediaController extends AbstractOfficialAccountController
{
    /**
     * 上传临时文件素材
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
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $miniProgram = $this->getOfficialAccount();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->officialAccountModel->app_id, $fileName, $type);
        return $miniProgram->media->upload($type, $file);
    }
}
