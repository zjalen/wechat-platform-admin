<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Models\Tester;

class TesterController extends AbstractOpenPlatformController
{
    /**
     * 获取小程序体验者
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $miniProgram = $this->getMiniProgramApplication();
        $testers = Tester::query()->where('app_id', $this->appId)->get();
        $result = $miniProgram->tester->list();
        if ($result['errcode'] != 0) {
            return $result;
        }
        $list = $result['members'];
        foreach ($list as &$item) {
            $userStr = $item['userstr'];
            /** @var Tester $tester */
            $tester = $testers->where('user_str', $userStr)->first();
            unset($item['userstr']);
            $item['user_str'] = $userStr;
            if ($tester) {
                $item['wechat_id'] = $tester->wechat_id;
            }
        }
        return $list;
    }

    /**
     * 添加小程序体验者
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store()
    {
        $wechatId = request('wechatId');
        $miniProgram = $this->getMiniProgramApplication();
        $result = $miniProgram->tester->bind($wechatId);
        if ($result['errcode'] == 0) {
            $userStr = $result['userstr'];
            $tester = Tester::query()->where('wechat_id', $wechatId)->where('app_id', $this->appId)->first();
            if (!$tester) {
                $tester = new Tester();
                $tester->app_id = $this->appId;
                $tester->user_str = $userStr;
                $tester->wechat_id = $wechatId;
            } else {
                $tester->user_str = $userStr;
            }
            $tester->save();
        }
        return $result;
    }

    /**
     * 删除小程序体验者
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \App\Exceptions\BusinessExceptions\WeChatException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy($opId, $appId, $userSlug)
    {
        $useWechatId = request()->query->get('useWechatId');
        $miniProgram = $this->getMiniProgramApplication();
        if ($useWechatId) {
            $result = $miniProgram->tester->unbind($userSlug);
            if ($result['errcode'] == 0) {
                $tester = Tester::query()->where('wechat_id', $userSlug)->where('app_id', $this->appId)->first();
                if ($tester) {
                    $tester->delete();
                }
            }
        } else {
            $result = $miniProgram->tester->unbind(null, $userSlug);
            if ($result['errcode'] == 0) {
                $tester = Tester::query()->where('user_str', $userSlug)->where('app_id', $this->appId)->first();
                if ($tester) {
                    $tester->delete();
                }
            }
        }
        return $result;
    }
}
