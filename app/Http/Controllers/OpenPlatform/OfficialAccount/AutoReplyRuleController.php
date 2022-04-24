<?php

namespace App\Http\Controllers\OpenPlatform\OfficialAccount;

use App\Exceptions\BusinessExceptions\NotFoundException;
use App\Http\Controllers\OpenPlatform\AbstractOpenPlatformController;
use App\Models\AutoReplyRule;

class AutoReplyRuleController extends AbstractOpenPlatformController
{
    /**
     * 获取所有规则
     *
     * @return array
     */
    public function index(): array
    {
        $appId = request()->route('appId');
        $limit = request('limit', 20);
        $skip = request('skip', 0);
        $orderBy = request('orderBy', 'id');
        $direction = request('desc', 'true') === 'true' ? 'desc' : 'asc';
        $query = AutoReplyRule::query()->where('app_id', $appId);
        $total_account = $query->count();
        $query->limit($limit)->skip($skip);
        if ($orderBy) {
            $query = $query->orderBy($orderBy, $direction);
        }
        return ['total_account' => $total_account, 'list' => $query->get()];
    }

    /**
     * 添加新的规则
     *
     * @return bool
     */
    public function store(): bool
    {
        $autoReplyRule = new AutoReplyRule();
        $appId = request()->route('appId');
        $autoReplyRule->app_id = $appId;
        $autoReplyRule->name = request('name');
        $autoReplyRule->keyword = request('keyword');
        $autoReplyRule->match_type = request('match_type');
        $autoReplyRule->type = request('type');
        $autoReplyRule->content = request('content');
        return $autoReplyRule->save();
    }

    /**
     * 查看规则
     *
     * @return AutoReplyRule|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function show()
    {
        $id = request()->route('auto_reply_rule');
        $appId = request()->route('appId');
        return AutoReplyRule::query()->where('id', $id)->where('app_id', $appId)->first();
    }

    /**
     * 更新规则
     *
     * @return bool
     * @throws NotFoundException
     */
    public function update(): bool
    {
        $id = request()->route('auto_reply_rule');
        $appId = request()->route('appId');
        $autoReplyRule = AutoReplyRule::query()->where('id', $id)->where('app_id', $appId)->first();
        if (!$autoReplyRule) {
            throw new NotFoundException();
        }
        $autoReplyRule->name = request('name');
        $autoReplyRule->keyword = request('keyword');
        $autoReplyRule->match_type = request('match_type');
        $autoReplyRule->type = request('type');
        $autoReplyRule->content = request('content');
        return $autoReplyRule->save();
    }

    /**
     * 删除规则
     *
     * @return bool|mixed|null
     */
    public function destroy()
    {
        $id = request()->route('auto_reply_rule');
        $appId = request()->route('appId');
        return AutoReplyRule::query()->where('id', $id)->where('app_id', $appId)->delete();
    }
}
