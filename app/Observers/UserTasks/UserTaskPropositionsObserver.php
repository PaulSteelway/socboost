<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\UserTaskPropositions;

/**
 * Class UserTaskPropositionsObserver
 * @package App\Observers\UserUserTaskPropositions
 */
class UserTaskPropositionsObserver
{
    /**
     * @param UserTaskPropositions $userTaskProposition
     * @return array
     */
    private function getCacheKeys(UserTaskPropositions $userTaskProposition): array
    {
        return [];
    }

    /**
     * @param UserTaskPropositions $userTaskProposition
     * @return array
     */
    private function getCacheTags(UserTaskPropositions $userTaskProposition): array
    {
        return [];
    }

    /**
     * Listen to the UserTaskPropositions created event.
     *
     * @param UserTaskPropositions $userTaskProposition
     * @return void
     * @throws
     */
    public function created(UserTaskPropositions $userTaskProposition)
    {
        clearCacheByArray($this->getCacheKeys($userTaskProposition));
        clearCacheByTags($this->getCacheTags($userTaskProposition));
    }

    /**
     * Listen to the UserTaskPropositions deleting event.
     *
     * @param UserTaskPropositions $userTaskProposition
     * @return void
     * @throws
     */
    public function deleted(UserTaskPropositions $userTaskProposition)
    {
        clearCacheByArray($this->getCacheKeys($userTaskProposition));
        clearCacheByTags($this->getCacheTags($userTaskProposition));
    }

    /**
     * Listen to the UserTaskPropositions updating event.
     *
     * @param UserTaskPropositions $userTaskProposition
     * @return void
     * @throws
     */
    public function updated(UserTaskPropositions $userTaskProposition)
    {
        clearCacheByArray($this->getCacheKeys($userTaskProposition));
        clearCacheByTags($this->getCacheTags($userTaskProposition));
    }
}