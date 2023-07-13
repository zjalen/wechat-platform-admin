<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class QRController extends AbstractOpenPlatformController
{
    /**
     * 生成小程序码
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     */
    public function store()
    {
        $params = [
            'width' => request('width'),
            'auto_color' => request('auto_color'),
            'line_color' => request('line_color'),
            'is_hyaline' => request('is_hyaline'),
        ];
        $miniProgram = $this->getMiniProgram();
        $unlimited = request('unlimited', false);
        if ($unlimited) {
            $params['scene'] = request('scene');
            $params['page'] = request('path');
            return $miniProgram->app_code->getUnlimit($params['scene'], $params);
        } else {
            $params['path'] = request('path');
            return $miniProgram->app_code->get($params['path'], $params);
        }
    }
}
