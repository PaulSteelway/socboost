<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\TaskActions;

/**
 * Class TaskActionsObserver
 * @package App\Observers
 */
class TaskActionsObserver
{
    /**
     * @param TaskActions $taskActions
     */
    public function deleting(TaskActions $taskActions)
    {
        foreach ($taskActions->userTaskActions()->get() as $taskAction) {
            $taskAction->delete();
        }
    }

    /**
     * @param TaskActions $taskAction
     * @return array
     */
    private function getCacheKeys(TaskActions $taskAction): array
    {
        return [];
    }

    /**
     * @param TaskActions $taskAction
     * @return array
     */
    private function getCacheTags(TaskActions $taskAction): array
    {
        return [];
    }

    /**
     * Listen to the TaskActions created event.
     *
     * @param TaskActions $taskAction
     * @return void
     * @throws
     */
    public function created(TaskActions $taskAction)
    {
        clearCacheByArray($this->getCacheKeys($taskAction));
        clearCacheByTags($this->getCacheTags($taskAction));
    }

    /**
     * Listen to the TaskActions deleting event.
     *
     * @param TaskActions $taskAction
     * @return void
     * @throws
     */
    public function deleted(TaskActions $taskAction)
    {
        clearCacheByArray($this->getCacheKeys($taskAction));
        clearCacheByTags($this->getCacheTags($taskAction));
    }

    /**
     * Listen to the TaskActions updating event.
     *
     * @param TaskActions $taskAction
     * @return void
     * @throws
     */
    public function updated(TaskActions $taskAction)
    {
        clearCacheByArray($this->getCacheKeys($taskAction));
        clearCacheByTags($this->getCacheTags($taskAction));
    }
}