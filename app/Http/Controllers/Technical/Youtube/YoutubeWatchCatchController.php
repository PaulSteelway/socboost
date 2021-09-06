<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Technical\Youtube;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTasks\TaskActions;
use App\Models\Youtube\YoutubeVideoWatch;
use Illuminate\Http\Request;

/**
 * Class YoutubeWatchCatchController
 * @package App\Http\Controllers\Technical\Youtube
 */
class YoutubeWatchCatchController extends Controller
{
    /**
     * @param string $taskActionId
     * @param string $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(string $taskActionId, string $userId) {
        /** @var TaskActions $taskAction */
        $taskAction = TaskActions::find($taskActionId);

        if (null == $taskAction) {
            throw new \Exception('Task action not found');
        }

        return view('technical.youtube.watch_catch', [
            'taskAction'   => $taskAction,
            'userId'       => $userId,
        ]);
    }

    /**
     * @param string $taskActionId
     * @param string $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function catchEvent(string $taskActionId, string $userId)
    {
        /** @var TaskActions $taskAction */
        $taskAction = TaskActions::find($taskActionId);

        if (null == $taskAction) {
            throw new \Exception('Task action not found');
        }

        $user = User::find($userId);

        if (null == $user) {
            throw new \Exception('User not found');
        }

        YoutubeVideoWatch::updateOrCreate([
            'resource_url' => $taskAction->source_address,
            'user_id'      => $user->id,
        ]);

        return response('ok');
    }
}
