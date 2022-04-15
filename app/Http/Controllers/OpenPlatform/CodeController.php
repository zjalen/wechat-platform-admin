<?php

namespace App\Http\Controllers\OpenPlatform;

class CodeController extends AbstractOpenPlatformController
{
    /**
     * 获取代码草稿或模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $type = request()->query('type');
        $openPlatform = $this->getOpenPlatform();
        if ($type == 1) {
            // 标准模板
            return $openPlatform->code_template->list(1);
        } else if ($type == 0) {
            // 普通模板
            return $openPlatform->code_template->list(0);
        } else {
            // 草稿
            return $openPlatform->code_template->httpPostJson('wxa/gettemplatedraftlist');
        }
    }

    /**
     * 将草稿添加到模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update()
    {
        $type = request('type');
        $draftId = request('draftId');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->createFromDraft($draftId, $type);
    }

    /**
     * 删除代码模板
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy()
    {
        $templateId = request()->route('code');
        $openPlatform = $this->getOpenPlatform();
        return $openPlatform->code_template->delete($templateId);
    }
}
