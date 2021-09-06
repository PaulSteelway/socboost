<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\UserTasks;

use App\Models\UserTasks\UserTaskActions;
use App\Models\UserTasks\UserTasks;

/**
 * Class UserUserTaskActionsObserver
 * @package App\Observers
 */
class UserTaskActionsObserver
{
    /**
     * @param UserTaskActions $userTaskAction
     * @return array
     */
    private function getCacheKeys(UserTaskActions $userTaskAction): array
    {
        return [];
    }

    /**
     * @param UserTaskActions $userTaskAction
     * @return array
     */
    private function getCacheTags(UserTaskActions $userTaskAction): array
    {
        return [];
    }

    /**
     * Listen to the UserTaskActions created event.
     *
     * @param UserTaskActions $userTaskAction
     * @return void
     * @throws
     */
    public function created(UserTaskActions $userTaskAction)
    {
        clearCacheByArray($this->getCacheKeys($userTaskAction));
        clearCacheByTags($this->getCacheTags($userTaskAction));
    }

    /**
     * Listen to the UserTaskActions deleting event.
     *
     * @param UserTaskActions $userTaskAction
     * @return void
     * @throws
     */
    public function deleted(UserTaskActions $userTaskAction)
    {
        clearCacheByArray($this->getCacheKeys($userTaskAction));
        clearCacheByTags($this->getCacheTags($userTaskAction));
    }

    /**
     * Listen to the UserTaskActions updating event.
     *
     * @param UserTaskActions $userTaskAction
     * @return void
     * @throws
     */
    public function updated(UserTaskActions $userTaskAction)
    {
        clearCacheByArray($this->getCacheKeys($userTaskAction));
        clearCacheByTags($this->getCacheTags($userTaskAction));

        $this->updateMainTaskIfNeeded($userTaskAction);
    }

    /**
     * @param UserTaskActions $userTaskAction
     * @return bool
     * @throws \Throwable
     */
    private function updateMainTaskIfNeeded(UserTaskActions $userTaskAction)
    {
        /** @var UserTasks $userTask */
        $userTask = $userTaskAction->userTask()->first();

        if (null === $userTask) {
            return false;
        }

        if (1 !== $userTask->active) {
            return false;
        }

        /** @var UserTaskActions $otherUserTaskActions */
        $otherUserTaskActions = $userTask->userTaskActions()
            ->where('finished', 0)
            ->count();

        if ($otherUserTaskActions > 0) {
            return false;
        }

        $cacheKey = 'userTaskPayed.u-'.$userTaskAction->user_id.'.t-'.$userTaskAction->user_task_id;

        if (cache()->has($cacheKey) && true === cache($cacheKey)) {
            return false;
        }

        cache()->remember($cacheKey, 60, function () {
            return true;
        });

        $userTask->active = 0;
        $userTask->payed  = 1;
        $userTask->save();
        $userTask->sendReward();

        return true;
    }
}