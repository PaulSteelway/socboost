<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs\TaskCheck\VK;

use App\Jobs\TaskCheck\TaskCheckTrait;
use App\Modules\SocialNetworks\VkModule;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class VkPostLikeJob
 * @package App\Jobs\TaskCheck\VK
 */
class VkPostLikeJob implements ShouldQueue
{
    use TaskCheckTrait;

    /**
     * @return void
     */
    public function handle()
    {
        $this->prepare();

        try {
            $result = VkModule::checkPostLike($this->user, $this->taskAction->source_address);
        } catch (\Exception $e) {
            $this->fail((new \Exception($e->getMessage())));
            return;
        }

        $this->checkResult($result);
    }
}