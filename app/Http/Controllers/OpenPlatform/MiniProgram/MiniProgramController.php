<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class MiniProgramController extends AbstractOpenPlatformController
{
    /**
     * 获取已授权小程序基本信息
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function show()
    {
        $application = $this->getMiniProgramApplication();
        return $application->account->getBasicInfo();
    }

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
    public function uploadTemplateMedia()
    {
        $fileName = request()->input('fileName');
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $miniProgram = $this->getMiniProgramApplication();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->appId, $fileName, $type);
        return $miniProgram->media->upload($type, $file);
    }
}
