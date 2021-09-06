<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs\TaskCheck\Telegram;

use App\Jobs\TaskCheck\TaskCheckTrait;
use App\Modules\Messangers\TelegramModule;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class TelegramChannelSubscriptionJob
 * @package App\Jobs\TaskCheck\Telegram
 */
class TelegramChannelSubscriptionJob implements ShouldQueue
{
    use TaskCheckTrait;

    /**
     * @return void
     */
    public function handle()
    {
        $this->prepare();

        try {
            $result = TelegramModule::channelSubscription($this->user, $this->taskAction->source_address);
        } catch (\Exception $e) {
            $this->fail((new \Exception($e->getMessage())));
            return;
        }

        $this->checkResult($result);
    }
}