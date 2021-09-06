<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram\notification_bot;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\User;
use App\Modules\Messangers\TelegramModule;

class NotificationBotStartController extends Controller
{
    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramBotScopes $scope
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function index(TelegramWebhooks $webhook,
                          TelegramBots $bot,
                          TelegramBotScopes $scope,
                          TelegramUsers $telegramUser,
                          TelegramBotEvents $event)
    {
        $adminRole = \DB::table('roles')
            ->where('name', 'root')
            ->first();

        if (null == $adminRole) {
            return response('ok');
        }

        /** @var User $searchUser */
        $searchUser = $telegramUser->user;
        $authorized = \DB::table('model_has_roles')
            ->where('role_id', $adminRole->id)
            ->where('model_id', $searchUser->id)
            ->where('model_type', 'App\Models\User')
            ->count() > 0;

        if (true !== $authorized) {
            app()->call(NotificationBotAuthController::class.'@index', [
                'webhook'       => $webhook,
                'bot'           => $bot,
                'scope'         => $scope,
                'telegramUser'  => $telegramUser,
                'event'         => $event,
            ]);
            return response('ok');
        }

        TelegramModule::setLanguageLocale($event->from_language_code);
        $message = view('telegram.notification_bot.start', [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'scope'        => $scope,
            'telegramUser' => $telegramUser,
        ])->render();

        if (config('app.env') == 'develop') {
            \Log::info('Prepared VIEW and message for bot:<hr>'.$message);
        }

        try {
            $telegramInstance = new TelegramModule($bot->keyword);
            $telegramInstance->sendMessage($telegramUser->chat_id,
                $message,
                'HTML',
                false,
                false,
                null,
                null,
                $scope);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response('ok');
        }

        return response('ok');
    }
}
