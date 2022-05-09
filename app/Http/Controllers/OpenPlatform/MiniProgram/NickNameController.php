<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;

class NickNameController extends AbstractOpenPlatformController
{
    /**
     * 检查昵称是否可用
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show()
    {
        $nickName = request()->route('nick_name');
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->isAvailableNickname($nickName);
    }

    /**
     * 设置昵称
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update()
    {
        $nickName = request()->route('nick_name');
        $idCard = request()->input('id_card');
        $license = request()->input('license');
        $otherMediaArray = [];
        $other1 = request()->input('naming_other_stuff_1');
        $other2 = request()->input('naming_other_stuff_2');
        $other3 = request()->input('naming_other_stuff_3');
        $other4 = request()->input('naming_other_stuff_4');
        $other5 = request()->input('naming_other_stuff_5');
        if ($other1) {
            $otherMediaArray[] = $other1;
        }
        if ($other2) {
            $otherMediaArray[] = $other2;
        }
        if ($other3) {
            $otherMediaArray[] = $other3;
        }
        if ($other4) {
            $otherMediaArray[] = $other4;
        }
        if ($other5) {
            $otherMediaArray[] = $other5;
        }
        $miniProgram = $this->getMiniProgram();
        if ($license) {
            $response = $miniProgram->setting->setNickname($nickName, '', $license, $otherMediaArray);
        } else {
            $response = $miniProgram->setting->setNickname($nickName, $idCard, '', $otherMediaArray);
        }
        return $response;
    }

    /**
     * 昵称审核状态
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function auditStatus()
    {
        $auditId = request()->route('audit_id');
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->getNicknameAuditStatus($auditId);
    }
}
