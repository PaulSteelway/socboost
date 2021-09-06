<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic\TaskCheck;

use App\Models\User;
use App\Models\UserTasks\TaskActions;
use App\Models\UserTasks\TaskScopes;
use App\Models\UserTasks\UserTaskActions;
use App\Models\UserTasks\UserTasks;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckAllScopes extends Command
{
    const CHECK_TASKS_LIMIT = 1000;

    /** @var User|null */
    private $userId = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_check:all {userId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking all tasks.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $this->userId = (string) $this->argument('userId');

        $userTasks = UserTasks::where('active', 1)
            ->orderBy('end_datetime');

        if (!empty($this->userId)) {
            $userTasks = $userTasks->where('user_id', $this->userId);
        }

        $userTasks = $userTasks
            ->limit(self::CHECK_TASKS_LIMIT)
            ->get();

        if (0 === count($userTasks)) {
            $this->info('Active user tasks not found.');
            return;
        }

        $this->comment('Found '.count($userTasks).' user tasks.');

        /** @var UserTasks $userTask */
        foreach ($userTasks as $userTask) {
            if (Carbon::parse($userTask->end_datetime)->lessThan(now())) {
                $userTask->active = 0;
                $userTask->save();

                /** @var User $user */
                $user = $userTask->user;

                $user->sendNotification('notify_about_passed_task_deadline', [
                    'user'     => $user,
                    'userTask' => $userTask,
                ]);

                continue;
            }

            $userTaskActions = $userTask->userTaskActions()
                ->where('finished', 0)
                ->get();
            $this->comment('Found '.count($userTaskActions).' user task actions.');

            /** @var UserTaskActions $userTaskAction */
            foreach($userTaskActions as $userTaskAction) {
                /** @var TaskActions $taskAction */
                $taskAction = $userTaskAction->taskAction()->first();

                if (null === $taskAction) {
                    $msg = 'Task action not found for user task ID '.$userTaskAction->id;
                    $this->warn($msg);
                    \Log::error($msg);
                    continue;
                }

                /** @var TaskScopes $scope */
                $scope = $taskAction->scope()->first();

                if (null === $scope) {
                    $msg = 'Scope not found for task action ID '.$taskAction->id;
                    $this->warn($msg);
                    \Log::error($msg);
                    continue;
                }

                $this->call($scope->checker_command_name, [
                    'userTaskActionId' => $userTaskAction->id,
                ]);
                continue;
            }
        }
    }
}