<?php

namespace App\Jobs;

use App\Exceptions\BusinessExceptions\WeChatException;
use App\Models\Platform;
use App\Services\ThirdApi\OpenPlatformService;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCustomMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Platform */
    private $openPlatformModel;

    /** @var string */
    private $appId;

    /** @var string */
    private $openId;

    /** @var Message */
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Platform $openPlatformModel, $appId, $openId, $message)
    {
        $this->openPlatformModel = $openPlatformModel;
        $this->appId = $appId;
        $this->openId = $openId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $app = (new OpenPlatformService($this->openPlatformModel))->getOfficialAccountApplication($this->appId);
            $app->customer_service->message($this->message)->to($this->openId)->send();
        } catch (InvalidArgumentException|InvalidConfigException|RuntimeException|WeChatException $e) {
            Log::error($e);
        }
    }
}
