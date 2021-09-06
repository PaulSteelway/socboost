<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\UserIp;

/**
 * Class UserIpObserver
 * @package App\Observers
 */
class UserIpObserver
{
    /**
     * Listen to the UserIp created event.
     *
     * @param UserIp $userIp
     * @return void
     * @throws
     */
    public function created(UserIp $userIp)
    {
        clearCacheByArray($this->getCacheKeys($userIp));
        clearCacheByTags($this->getCacheTags($userIp));
    }

    /**
     * @param UserIp $userIp
     * @return array
     */
    private function getCacheKeys(UserIp $userIp): array
    {
        return [
            'i.' . $userIp->user_id . '.userIps',
        ];
    }

    /**
     * @param UserIp $userIp
     * @return array
     */
    private function getCacheTags(UserIp $userIp): array
    {
        return [];
    }

    /**
     * Listen to the UserIp deleting event.
     *
     * @param UserIp $userIp
     * @return void
     * @throws
     */
    public function deleted(UserIp $userIp)
    {
        clearCacheByArray($this->getCacheKeys($userIp));
        clearCacheByTags($this->getCacheTags($userIp));
    }

    /**
     * Listen to the UserIp updating event.
     *
     * @param UserIp $userIp
     * @return void
     * @throws
     */
    public function updated(UserIp $userIp)
    {
        clearCacheByArray($this->getCacheKeys($userIp));
        clearCacheByTags($this->getCacheTags($userIp));
    }
}