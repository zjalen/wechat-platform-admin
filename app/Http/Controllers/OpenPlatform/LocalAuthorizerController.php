<?php

namespace App\Http\Controllers\OpenPlatform;

use App\Models\Authorizer;
use App\Models\Platform;
use Illuminate\Support\Str;
use function request;

class LocalAuthorizerController extends AbstractOpenPlatformController
{
    /**
     * 获取所有已授权平台信息
     *
     * @return array
     */
    public function index(): array
    {
        $limit = \request('limit', 50);
        $skip = \request('skip', 0);
        /** @var Platform $openPlatformModel */
        $openPlatformModel = request()->attributes->get('openPlatform');
        $totalCount = $openPlatformModel->localAuthorizers()->count();
        $list = $openPlatformModel->localAuthorizers()->limit($limit)->skip($skip)->get();
        return ['total_count' => $totalCount, 'list' => $list];
    }

    /**
     * 写入授权平台到数据库
     *
     * @return Authorizer
     */
    public function store(): Authorizer
    {
        $this->getOpenPlatform();
        $params = request()->input();
        $subPlatform = Authorizer::whereAppId($params['app_id'])->where('platform_id', $this->openPlatformModel->id)->first();
        if (!$subPlatform) {
            $subPlatform = new Authorizer();
            $subPlatform->slug = Str::random();
        }
        $subPlatform->fill($params);
        $subPlatform->platform_id = $this->openPlatformModel->id;
        $subPlatform->status = 1;
        $subPlatform->save();
        return $subPlatform;
    }

    /**
     * 查看某个已授权平台
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\HasMany[]|null
     */
    public function show($id)
    {
        $this->getOpenPlatform();
        return $this->openPlatformModel->localAuthorizers()->find($id);
    }

    /**
     * 删除已授权平台
     *
     * @return int
     */
    public function destroy(): int
    {
        $id = request()->route('sub_platform');
        return Authorizer::destroy($id);
    }
}
