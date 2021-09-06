<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use Yajra\DataTables\DataTables;

/**
 * Class MessagesController
 * @package App\Http\Controllers\Admin\Telegram
 */
class MessagesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.messages.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        /** @var TelegramBotMessages $messages */
        $messages = TelegramBotMessages::select('*');

        return DataTables::of($messages)
            ->editColumn('sender', function ($message) {
                if ($message->sender == 'bot') {
                    /** @var TelegramBots $bot */
                    $bot = $message->bot()->first();

                    if (null === $bot) {
                        return __('bot not found');
                    }
                    return $bot->username;
                }

                /** @var TelegramUsers $telegramUser */
                $telegramUser = TelegramUsers::where('telegram_user_id', $message->sender)
                    ->first();

                if (null === $telegramUser) {
                    return __('user not found');
                }

                /** @var TelegramBotEvents $lastEvent */
                $lastEvent = TelegramBotEvents::where('from_id', $telegramUser->telegram_user_id)
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
                    ->first();

                return $lastEvent->from_username;
            })
            ->editColumn('receive', function ($message) {
                if ($message->receive == 'bot') {
                    /** @var TelegramBots $bot */
                    $bot = $message->bot()->first();

                    if (null === $bot) {
                        return __('bot not found');
                    }
                    return $bot->username;
                }

                /** @var TelegramUsers $telegramUser */
                $telegramUser = TelegramUsers::where('telegram_user_id', $message->receive)
                    ->first();

                if (null === $telegramUser) {
                    return __('user not found');
                }

                /** @var TelegramBotEvents $lastEvent */
                $lastEvent = TelegramBotEvents::where('from_id', $telegramUser->telegram_user_id)
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
                    ->first();

                return $lastEvent->from_username;
            })
            ->addColumn('scope', function ($message) {
                /** @var TelegramBotScopes $scope */
                $scope = $message->scope()->first();

                if (null === $scope) {
                    return __('scope not found');
                }

                return __($scope->description);
            })
            ->make(true);
    }
}
