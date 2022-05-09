<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class AvatarController extends AbstractOpenPlatformController
{
    /**
     * 设置头像
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update()
    {
        $miniProgram = $this->getMiniProgram();
        $head_img_media_id = request()->input('head_img_media_id');
        $x1 = request()->input('x1');
        $y1 = request()->input('y1');
        $x2 = request()->input('x2');
        $y2 = request()->input('y2');
        if ($x1 || $x2 || $y1 || $y2) {
            return $miniProgram->account->updateAvatar($head_img_media_id, $x1, $y1, $x2, $y2);
        }
        return $miniProgram->account->updateAvatar($head_img_media_id);
    }
}
