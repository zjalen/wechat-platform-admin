<?php


namespace App\Services;


use App\Models\Platform;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/16 14:26
 */
class PlatformService
{
    /**
     * 获取开放平台数据模型
     * @param  string  $opSlug
     * @return Platform|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOpenPlatformBySlug(string $opSlug)
    {
        if (!$opSlug) {
            return null;
        }
        return Platform::query()->where('slug', $opSlug)->where('type', Platform::TYPE_THIRD_PARTY)->first();
    }

    /**
     * 获取开放平台数据模型
     * @param  int  $id
     * @return Platform|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOpenPlatformById(int $id)
    {
        if (!$id) {
            return null;
        }
        return Platform::query()->where('id', $id)->where('type', Platform::TYPE_THIRD_PARTY)->first();
    }

    /**
     * 获取公众号数据模型
     * @param  string  $opSlug
     * @return Platform|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOfficialAccountBySlug(string $opSlug)
    {
        if (!$opSlug) {
            return null;
        }
        return Platform::query()->where('slug', $opSlug)->where('type', Platform::TYPE_OFFICIAL_ACCOUNT)->first();
    }

    /**
     * 获取公众号数据模型
     * @param  int  $id
     * @return Platform|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOfficialAccountById(int $id)
    {
        if (!$id) {
            return null;
        }
        return Platform::query()->where('id', $id)->where('type', Platform::TYPE_OFFICIAL_ACCOUNT)->first();
    }
}
