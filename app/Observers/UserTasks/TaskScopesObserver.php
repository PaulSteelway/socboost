<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\TaskScopes;

/**
 * Class TaskScopesObserver
 * @package App\Observers
 */
class TaskScopesObserver
{
    /**
     * @param TaskScopes $taskScope
     */
    public function deleting(TaskScopes $taskScope)
    {
        foreach ($taskScope->actions()->get() as $action) {
            $action->delete();
        }
    }

    /**
     * @param TaskScopes $taskScope
     * @return array
     */
    private function getCacheKeys(TaskScopes $taskScope): array
    {
        return [];
    }

    /**
     * @param TaskScopes $taskScope
     * @return array
     */
    private function getCacheTags(TaskScopes $taskScope): array
    {
        return [];
    }

    /**
     * Listen to the TaskScopes created event.
     *
     * @param TaskScopes $taskScope
     * @return void
     * @throws
     */
    public function created(TaskScopes $taskScope)
    {
        clearCacheByArray($this->getCacheKeys($taskScope));
        clearCacheByTags($this->getCacheTags($taskScope));
    }

    /**
     * Listen to the TaskScopes deleting event.
     *
     * @param TaskScopes $taskScope
     * @return void
     * @throws
     */
    public function deleted(TaskScopes $taskScope)
    {
        clearCacheByArray($this->getCacheKeys($taskScope));
        clearCacheByTags($this->getCacheTags($taskScope));
    }

    /**
     * Listen to the TaskScopes updating event.
     *
     * @param TaskScopes $taskScope
     * @return void
     * @throws
     */
    public function updated(TaskScopes $taskScope)
    {
        clearCacheByArray($this->getCacheKeys($taskScope));
        clearCacheByTags($this->getCacheTags($taskScope));
    }
}