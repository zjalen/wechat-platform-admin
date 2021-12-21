<?php

namespace App\Listeners;

use App\Events\SubPlatformAuthorized;
use App\Models\SubPlatform;
use App\Services\ThirdApi\OpenPlatformService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class SubPlatformBuild
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SubPlatformAuthorized  $event
     * @return void
     */
    public function handle(SubPlatformAuthorized $event)
    {
        $openPlatformModel = $event->openPlatformModel;
        $appId = $event->appId;
        $openPlatform = new OpenPlatformService($openPlatformModel);
        $authorizer = $openPlatform->getAuthorizer($appId);
        $subPlatform = SubPlatform::whereAppId($appId)->first();
        if (!$subPlatform) {
            $subPlatform = new SubPlatform();
            $subPlatform->slug = Str::random();
        }
        $subPlatform->app_id = $appId;
        $subPlatform->nick_name = $authorizer->authorizer_info->nick_name;
        $subPlatform->head_img = $authorizer->authorizer_info->head_img;
        $subPlatform->principal_name = $authorizer->authorizer_info->principal_name;
        $subPlatform->qrcode_url = $authorizer->authorizer_info->qrcode_url;
        $subPlatform->user_name = $authorizer->authorizer_info->user_name;
        $subPlatform->service_type_info = $authorizer->authorizer_info->service_type_info->id;
        $subPlatform->is_mini_program = !!$authorizer->authorizer_info->MiniProgramInfo ? 1 : 0;
        $subPlatform->platform_id = $openPlatformModel->id;
        $subPlatform->status = 1;
        $subPlatform->save();
    }
}
