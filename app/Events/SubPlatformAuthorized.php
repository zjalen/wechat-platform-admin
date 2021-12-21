<?php

namespace App\Events;

use App\Models\Platform;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubPlatformAuthorized
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Platform 开放平台模型
     */
    public $openPlatformModel;

    /**
     * @var string $appId 已绑定的账号 id
     */
    public $appId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Platform $openPlatform, string $appId)
    {
        $this->openPlatformModel = $openPlatform;
        $this->appId = $appId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
