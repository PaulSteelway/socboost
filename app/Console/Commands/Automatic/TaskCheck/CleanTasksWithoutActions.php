<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic\TaskCheck;

use App\Models\User;
use App\Models\UserTasks\UserTasks;
use Illuminate\Console\Command;

class CleanTasksWithoutActions extends Command
{
    /** @var User|null */
    private $userId = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_check:clean_without_actions {userId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleaning tasks without actions.';

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
            ->where('payed', 0);

        if (!empty($this->userId)) {
            $userTasks = $userTasks->where('user_id', $this->userId);
        }

        $userTasks = $userTasks
            ->orderBy('end_datetime')
            ->get();

        if (0 === count($userTasks)) {
            $this->info('Active user tasks not found.');
            return;
        }

        $this->comment('Found '.count($userTasks).' user tasks.');

        /** @var UserTasks $userTask */
        foreach ($userTasks as $userTask) {
            if ($userTask->userTaskActions()->count() == 0) {
                $this->comment('User task '.$userTask->id.' deleted, because this task hasn\'t any actions.');
                $userTask->delete();
            }
        }
    }
}