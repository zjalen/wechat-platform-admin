<?php

namespace App\Listeners;

use App\Events\SubPlatformUnAuthorized;
use App\Models\SubPlatform;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubPlatformStatusReset
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
     * @param  \App\Events\SubPlatformUnAuthorized  $event
     * @return void
     */
    public function handle(SubPlatformUnAuthorized $event)
    {
        $openPlatformModel = $event->openPlatformModel;
        $appId = $event->appId;
        SubPlatform::query()->where('app_id', $appId)->where('platform_id', $openPlatformModel->id)->update(['status' => 0]);
    }
}
