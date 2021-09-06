<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\Tasks;

/**
 * Class TasksObserver
 * @package App\Observers
 */
class TasksObserver
{
    /**
     * @param Tasks $task
     */
    public function deleting(Tasks $task)
    {
        foreach ($task->actions()->get() as $action) {
            $action->delete();
        }

        foreach ($task->userTasks()->get() as $userTask) {
            $userTask->delete();
        }

        foreach ($task->userTaskPropositions()->get() as $taskProposition) {
            $taskProposition->delete();
        }

        foreach ($task->coefficients()->get() as $coefficient) {
            $coefficient->delete();
        }
    }

    /**
     * @param Tasks $task
     * @return array
     */
    private function getCacheKeys(Tasks $task): array
    {
        return [];
    }

    /**
     * @param Tasks $task
     * @return array
     */
    private function getCacheTags(Tasks $task): array
    {
        return [];
    }

    /**
     * Listen to the Tasks created event.
     *
     * @param Tasks $task
     * @return void
     * @throws
     */
    public function created(Tasks $task)
    {
        clearCacheByArray($this->getCacheKeys($task));
        clearCacheByTags($this->getCacheTags($task));
    }

    /**
     * Listen to the Tasks deleting event.
     *
     * @param Tasks $task
     * @return void
     * @throws
     */
    public function deleted(Tasks $task)
    {
        clearCacheByArray($this->getCacheKeys($task));
        clearCacheByTags($this->getCacheTags($task));
    }

    /**
     * Listen to the Tasks updating event.
     *
     * @param Tasks $task
     * @return void
     * @throws
     */
    public function updated(Tasks $task)
    {
        clearCacheByArray($this->getCacheKeys($task));
        clearCacheByTags($this->getCacheTags($task));
    }
}