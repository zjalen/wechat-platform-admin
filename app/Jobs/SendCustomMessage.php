<?php

namespace App\Jobs;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCustomMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Application */
    private $application;

    /** @var string */
    private $openId;

    /** @var Message */
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Application $application, $openId, $message)
    {
        $this->application = $application;
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
            $this->application->customer_service->message($this->message)->to($this->openId)->send();
        } catch (InvalidArgumentException|InvalidConfigException|RuntimeException $e) {
            Log::error($e);
        }
    }
}
