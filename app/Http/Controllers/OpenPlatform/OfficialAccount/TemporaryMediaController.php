<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class TemporaryMediaController extends AbstractOpenPlatformController
{
    /**
     * 上传临时文件素材
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
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $miniProgram = $this->getOfficialAccount();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->appId, $fileName, $type);
        return $miniProgram->media->upload($type, $file);
    }
}
