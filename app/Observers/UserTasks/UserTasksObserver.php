<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\TaskActions;
use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\UserTaskActions;
use App\Models\UserTasks\UserTasks;

/**
 * Class UserTasksObserver
 * @package App\Observers
 */
class UserTasksObserver
{
    /**
     * @param UserTasks $userTask
     */
    public function deleting(UserTasks $userTask)
    {
        foreach ($userTask->userTaskActions()->get() as $task) {
            $task->delete();
        }
    }

    /**
     * @param UserTasks $userTask
     * @return array
     */
    private function getCacheKeys(UserTasks $userTask): array
    {
        return [];
    }

    /**
     * @param UserTasks $userTask
     * @return array
     */
    private function getCacheTags(UserTasks $userTask): array
    {
        return [];
    }

    /**
     * Listen to the UserTasks created event.
     *
     * @param UserTasks $userTask
     * @return void
     * @throws
     */
    public function created(UserTasks $userTask)
    {
        /*
         * Creating user task actions
         */
        /** @var Tasks $task */
        $task = $userTask->task;

        /** @var TaskActions $action */
        foreach ($task->actions()->get() as $action) {
            UserTaskActions::create([
                'user_id'             => $userTask->user_id,
                'task_action_id'      => $action->id,
                'last_check_datetime' => now()->toDateTimeString(),
                'finished'            => 0,
                'user_task_id'        => $userTask->id,
            ]);
        }

        if ($userTask->userTaskActions()->count() == 0) {
            $userTask->delete();
        }

        clearCacheByArray($this->getCacheKeys($userTask));
        clearCacheByTags($this->getCacheTags($userTask));
    }

    /**
     * Listen to the UserTasks deleting event.
     *
     * @param UserTasks $userTask
     * @return void
     * @throws
     */
    public function deleted(UserTasks $userTask)
    {
        clearCacheByArray($this->getCacheKeys($userTask));
        clearCacheByTags($this->getCacheTags($userTask));
    }

    /**
     * Listen to the UserTasks updating event.
     *
     * @param UserTasks $userTask
     * @return void
     * @throws
     */
    public function updated(UserTasks $userTask)
    {
        clearCacheByArray($this->getCacheKeys($userTask));
        clearCacheByTags($this->getCacheTags($userTask));
    }
}