<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic\TaskCheck;

use App\Models\User;
use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\UserTaskPropositions;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveDeadTasksFromRejectedCommand extends Command
{
    /** @var User|null */
    private $userId = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_check:clean_dead_task_propositions {userId?}';

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

        if (!empty($this->userId)) {
            $userPropositions = UserTaskPropositions::where('user_id', $this->userId)->get();
        } else {
            $userPropositions = UserTaskPropositions::orderBy('created_at')->get();
        }

        if (0 === count($userPropositions)) {
            $this->info('Active task propositions not found.');
            return;
        }

        $this->comment('Found '.count($userPropositions).' task propositions.');

        /** @var UserTaskPropositions $userProposition */
        foreach ($userPropositions as $userProposition) {
            /** @var Tasks $task */
            $task = $userProposition->task()->first();

            if (null === $task) {
                $userProposition->delete();
            }

            if (Carbon::parse($task->deadline)->lessThan(now())) {
                $userProposition->delete();
            }
        }
    }
}