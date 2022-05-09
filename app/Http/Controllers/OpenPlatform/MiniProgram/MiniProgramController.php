<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Models\Authorizer;
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
        $application = $this->getMiniProgram();
        $appId = request()->route('appId');
        $authorizer = Authorizer::query()->where('app_id', $appId)->first();
        $basicInfo = $application->account->getBasicInfo();
        if ($authorizer && $basicInfo) {
            $basicInfo['is_auto_reply_open'] = $authorizer->is_auto_reply_open;
        }
        return $basicInfo;
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
    public function uploadTemporaryMedia()
    {
        $fileName = request()->input('fileName');
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $miniProgram = $this->getMiniProgram();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->appId, $fileName, $type);
        return $miniProgram->media->upload($type, $file);
    }
}
