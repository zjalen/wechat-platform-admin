<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Services\MediaService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class CodeController extends AbstractOpenPlatformController
{
    /**
     * 设为体验版代码
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function commit()
    {
        $templateId = request()->input('template_id');
        $extJson = request()->input('ext_json');
        $userVersion = request()->input('user_version');
        $userDesc = request()->input('user_desc');
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->commit($templateId, $extJson, $userVersion, $userDesc);
    }

    /**
     * 获取已上传代码的页面列表
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function getPage()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->getPage();
    }

    /**
     * 获取体验版二维码
     *
     * @return \EasyWeChat\Kernel\Http\Response
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function getQrCode(): \EasyWeChat\Kernel\Http\Response
    {
        $path = request()->input('path');
        $miniProgram = $this->getMiniProgramApplication();

        return $miniProgram->code->getQrCode($path);
    }

    /**
     * 上传审核专用临时文件素材
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws ParamsErrorException
     * @throws WeChatException
     */
    public function uploadAuditMedia()
    {
        $fileName = request()->input('fileName');
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $miniProgram = $this->getMiniProgramApplication();
        $mediaService = new MediaService();
        $file = $mediaService->getFilePath($this->appId, $fileName, $type);
        return $miniProgram->code->httpUpload('wxa/uploadmedia', [$fileName => $file]);
    }

    /**
     * 提交审核
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function audit()
    {
        $data = request()->only([
            'version_desc', 'preview_info', 'item_list', 'ugc_declare', 'feedback_info', 'feedback_stuff'
        ]);
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->submitAudit($data);
    }

    /**
     * 撤销审核
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function withdrawAudit()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->withdrawAudit();
    }

    /**
     * 查询指定版本审核状态
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     * @throws GuzzleException
     */
    public function getAuditStatus()
    {
        $auditId = request()->input('audit_id');
        $miniProgram = $this->getMiniProgramApplication();
        if (!$auditId) {
            return $miniProgram->code->getLatestAuditStatus();
        }
        return $miniProgram->code->getAuditStatus($auditId);
    }

    /**
     * 线上发布
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function release()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->release();
    }

    /**
     * 获取可回滚版本
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     * @throws GuzzleException
     */
    public function getReleaseHistoryVersions()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->httpGet('wxa/revertcoderelease', ['action' => 'get_history_version']);
    }

    /**
     * 已发布版本回滚
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     * @throws GuzzleException
     */
    public function revertReleaseVersion()
    {
        $app_version = request('app_version');
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->httpGet('wxa/revertcoderelease', ['app_version' => $app_version]);
    }

    /**
     * 分阶段发布，灰度发布
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function grayRelease()
    {
        $gray_percentage = request('gray_percentage');
        $support_experiencer_first = request('support_experiencer_first', false);
        $support_debugger_first = request('support_debuger_first', false);
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->httpPostJson('wxa/grayrelease', [
            'gray_percentage' => $gray_percentage,
            'support_experiencer_first' => $support_experiencer_first,
            'support_debuger_first' => $support_debugger_first
        ]);
    }

    /**
     * 查询分阶段发布详情
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function getGrayRelease()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->getGrayRelease();
    }

    /**
     * 撤销分阶段发布
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function revertGrayRelease()
    {
        $miniProgram = $this->getMiniProgramApplication();
        return $miniProgram->code->revertGrayRelease();
    }
}
