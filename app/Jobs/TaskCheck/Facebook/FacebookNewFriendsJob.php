<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs\TaskCheck\Facebook;

use App\Jobs\TaskCheck\TaskCheckTrait;
use App\Modules\SocialNetworks\FacebookModule;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class FacebookNewFriendsJob
 * @package App\Jobs\TaskCheck\Facebook
 */
class FacebookNewFriendsJob implements ShouldQueue
{
    use TaskCheckTrait;

    /**
     * @return void
     */
    public function handle()
    {
        $this->prepare();

        try {
            $result = FacebookModule::checkNewFriends($this->user, $this->taskAction->source_address);
        } catch (\Exception $e) {
            $this->fail((new \Exception($e->getMessage())));
            return;
        }

        $this->checkResult($result);
    }
}