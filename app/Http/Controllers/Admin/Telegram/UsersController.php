<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramUsers;
use App\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class UsersController
 * @package App\Http\Controllers\Admin\Telegram
 */
class UsersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.users.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        /** @var TelegramUsers $users */
        $users = TelegramUsers::select('*')->with([
            'user',
            'bot',
        ]);

        return DataTables::of($users)
            ->addColumn('bot_username', function($user) {
                /** @var TelegramBots $bot */
                $bot = $user->bot;

                if (null === $bot) {
                    return __('bot not found');
                }
                return $bot->username;
            })
            ->addColumn('user_name', function($user) {
                /** @var User $bot */
                $user = $user->user;

                if (null === $user) {
                    return __('user not found');
                }
                return $user->login;
            })
            ->make(true);
    }
}
