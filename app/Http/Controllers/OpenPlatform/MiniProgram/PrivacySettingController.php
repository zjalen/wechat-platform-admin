<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class PrivacySettingController extends AbstractOpenPlatformController
{
    /**
     * 获取隐私保护指引
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function index()
    {
        $privacyVer = request('privacyVer') ?: 2;
        $data = ['privacy_ver' => $privacyVer];
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->setting->httpPostJson('cgi-bin/component/getprivacysetting', $data);
    }

    /**
     * 设置隐私保护指引
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function store()
    {
        $data = request()->only(['privacy_ver', 'owner_setting', 'setting_list']);
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->setting->httpPostJson('cgi-bin/component/setprivacysetting', $data);
    }

    /**
     * 上传自定义隐私保护指引
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function uploadPrivacyExtFile()
    {
        $privacyFile = request()->file('file');
        $miniProgram = $this->getMiniProgramApplication();
        $filePath = Storage::putFileAs('privacy/'.$this->appId, $privacyFile, 'privacy.txt');
        $fullPath = Storage::path($filePath);
        $data['file'] = $fullPath;
        return $miniProgram->setting->httpUpload('cgi-bin/component/uploadprivacyextfile', $data);
    }
}
