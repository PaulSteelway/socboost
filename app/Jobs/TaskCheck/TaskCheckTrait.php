<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs\TaskCheck;

use App\Jobs\RefreshOauthTokens\RefreshGoogleTokenJob;
use App\Jobs\SendLogsJob;
use App\Models\User;
use App\Models\UsersSocialMeta;
use App\Models\UserTasks\TaskActions;
use App\Models\UserTasks\UserTaskActions;
use App\Models\UserTasks\UserTasks;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

trait TaskCheckTrait
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /** @var UserTaskActions $userTaskAction */
    public $userTaskAction = null;

    /** @var UserTasks $userTask */
    public $userTask = null;

    /** @var TaskActions $taskAction */
    public $taskAction = null;

    /** @var User $user */
    public $user = null;

    /**
     * YoutubeChannelSubscriptionJob constructor.
     * @param UserTaskActions $userTaskAction
     */
    public function __construct(UserTaskActions $userTaskAction)
    {
        /** @var UserTaskActions userTaskAction */
        $this->userTaskAction = $userTaskAction;
        /** @var UserTasks userTask */
        $this->userTask       = $this->userTaskAction->userTask()->firstOrFail();
        /** @var TaskActions taskAction */
        $this->taskAction     = $this->userTaskAction->taskAction()->firstOrFail();
        /** @var User user */
        $this->user           = $this->userTaskAction->user()->firstOrFail();
    }

    /**
     * @return void
     */
    public function prepare()
    {
        if (1 == $this->userTaskAction->finished || 0 == $this->userTask->active) {
            \Log::error('Trying to execute finished task. User task ID '.$this->userTask->id);
            return;
        }
        return;
    }

    /**
     * @param bool $result
     * @return void
     */
    public function checkResult(bool $result)
    {
        if (true !== $result) {
            $this->userTaskAction->last_check_datetime = now();
            $this->userTaskAction->save();
            return;
        }

        if (1 == $this->userTaskAction->finished) {
            return;
        }

        $this->userTaskAction->last_check_datetime = now();
        $this->userTaskAction->finished = 1;
        $this->userTaskAction->save();
        return;
    }

    /**
     * @param null $exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fail($exception = null)
    {
        $sentToRefresh = false;

        /*
         * Google refresh
         */
        if (preg_match('/(youtube|google)/', $this->taskAction->source_address)) {
            $whenTokenBeenRefreshed = UsersSocialMeta::getValue($this->user, 'google_oauth_token_refreshed', null);

            if (null !== $whenTokenBeenRefreshed && preg_match('/[0-9]+\-[0-9]+\-[0-9]+ [0-9]+\:[0-9]+\:[0-9]+/', $whenTokenBeenRefreshed)) {
                $now        = now();
                $refreshed  = Carbon::parse($whenTokenBeenRefreshed);

                if ($now->greaterThan($refreshed->subHour())) {
                    try {
                        $refreshClass = new RefreshGoogleTokenJob($this->user);
                        $refreshClass->handle();
                        $sentToRefresh = true;
                    } catch (\Exception $e) {
                        $sentToRefresh = false;
                    }
                }
            }
        }

        if (false === $sentToRefresh) {
            $this->user->sendNotification('task_check_failed', [
                'userTask' => $this->userTask,
                'userTaskAction' => $this->userTaskAction,
                'taskAction' => $this->taskAction,
                'user' => $this->userTaskAction,
            ]);
        }
    }
}