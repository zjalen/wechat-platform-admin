<?php

namespace App\Http\Controllers\OpenPlatform\MiniProgram;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;

class CategoryController extends AbstractOpenPlatformController
{
    /**
     * 获取已设置的类目
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws WeChatException
     */
    public function show()
    {
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->getCategories();
    }

    /**
     * 获取可用类目
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function index()
    {
        $type = request('type');
        $miniProgram = $this->getMiniProgram();
        if ($type) {
            return $miniProgram->setting->httpPostJson('cgi-bin/wxopen/getcategoriesbytype',
                ['verify_type' => (int) $type]);
        }
        return $miniProgram->setting->getAllCategories();
    }

    /**
     * 删除已设置的类目
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function destroy()
    {
        $first = request('first');
        $second = request('second');
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->deleteCategories($first, $second);
    }

    /**
     * 添加经营类目
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws WeChatException
     */
    public function store()
    {
        $categories = request('categories');
        $miniProgram = $this->getMiniProgram();
        return $miniProgram->setting->addCategories($categories);
    }
}
