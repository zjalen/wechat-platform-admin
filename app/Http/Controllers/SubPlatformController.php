<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\SubPlatform;
use Illuminate\Support\Str;

class SubPlatformController extends Controller
{
    /**
     * 获取请求中附带的开放平台信息
     * @return Platform
     */
    private function getOpenPlatformModel(): Platform
    {
        return request()->attributes->get('openPlatform');
    }

    public function index(): array
    {
        $limit = \request('limit', 50);
        $skip = \request('skip', 0);
        /** @var Platform $openPlatformModel */
        $openPlatformModel = request()->attributes->get('openPlatform');
        $totalCount = $openPlatformModel->subPlatforms()->count();
        $list = $openPlatformModel->subPlatforms()->limit($limit)->skip($skip)->get();
        return ['total_count' => $totalCount, 'list' => $list];
    }

    public function store(): SubPlatform
    {
        $params = request()->input();
        $subPlatform = SubPlatform::whereAppId($params['app_id'])->where('platform_id', $this->getOpenPlatformModel()->id)->first();
        if (!$subPlatform) {
            $subPlatform = new SubPlatform();
            $subPlatform->slug = Str::random();
        }
        $subPlatform->fill($params);
        $subPlatform->platform_id = $this->getOpenPlatformModel()->id;
        $subPlatform->status = 1;
        $subPlatform->save();
        return $subPlatform;
    }

    public function destroy(): int
    {
        $id = request()->route('sub_platform');
        return SubPlatform::destroy($id);
    }
}
