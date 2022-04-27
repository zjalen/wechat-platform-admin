<?php


namespace App\Http\Controllers\OfficialAccount;


use App\Models\CustomMenu;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/4/14 11:18
 */
class CustomMenuController extends AbstractOfficialAccountController
{
    /**
     * 获取所有已保存的菜单快照
     *
     * @return CustomMenu[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $appId = request()->route('appId');
        return CustomMenu::query()->where('app_id', $appId)->get();
    }

    /**
     * 获取单个菜单快照
     *
     * @return CustomMenu|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function show()
    {
        $appId = request()->route('appId');
        $id = request()->route('custom_menu');
        return CustomMenu::query()->where('id', $id)->where('app_id', $appId)->first();
    }

    /**
     * 保存菜单快照内容
     *
     * @return bool
     */
    public function store(): bool
    {
        $appId = request()->route('appId');
        $customMenu = new CustomMenu();
        $customMenu->app_id = $appId;
        $customMenu->remark = request('remark');
        $customMenu->content = request('content');
        return $customMenu->save();
    }

    /**
     * 删除快照
     * @return bool|mixed|null
     */
    public function destroy()
    {
        $appId = request()->route('appId');
        $id = request()->route('custom_menu');
        return CustomMenu::query()->where('id', $id)->where('app_id', $appId)->delete();
    }

    /**
     * 获取公众号发布的菜单列表
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function showPublishedMenu()
    {
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->menu->list();
    }

    /**
     * 发布到线上
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function publish()
    {
        $content = request('content');
        $matchRules = request()->only(['tag_id', 'platform_id']);
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->menu->create($content, $matchRules);
    }

    /**
     * 删除线上的菜单
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroyPublishedMenu()
    {
        $officialAccount = $this->getOfficialAccount();
        return $officialAccount->menu->delete();
    }
}
