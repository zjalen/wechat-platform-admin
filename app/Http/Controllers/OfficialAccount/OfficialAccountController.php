<?php

namespace App\Http\Controllers\OfficialAccount;

use App\Models\Platform;
use Illuminate\Support\Facades\Log;

class OfficialAccountController extends AbstractOfficialAccountController
{
    /**
     * 获取公众号的基本信息
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function show(): array
    {
        $officialAccount = $this->getOfficialAccount();
        $error = null;
        try {
            $token = $officialAccount->access_token->getToken();
        } catch (\Throwable $e) {
            Log::error($e);
            $token = null;
            $error = $e->getMessage();
        }
        return $this->officialAccountModel->toArray() +
            [
                'domain' => config('app.url'),
                'serve_url' => route('officialAccountNotify', ['officialAccountSlug' => $this->officialAccountModel->slug]),
                'access_token' => $token,
                'errMsg' => $error
            ];
    }

    /**
     * 更新自动回复状态
     *
     * @return bool
     */
    public function updateAutoReplyConfig(): bool
    {
        $openFlag = request('is_auto_reply_open');
        /** @var Platform $officialAccountModel */
        $officialAccountModel = request()->attributes->get('officialAccountModel');
        $officialAccountModel->is_auto_reply_open = $openFlag;
        return $officialAccountModel->save();
    }
}
