<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram\notification_bot;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\User;
use App\Modules\Messangers\TelegramModule;

class NotificationBotAuthController extends Controller
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
        $scope = TelegramBotScopes::where('bot_keyword', $bot->keyword)
            ->where('command', '/auth')
            ->first();

        TelegramModule::setLanguageLocale($event->from_language_code);
        $message = view('telegram.notification_bot.auth.login.index', [
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

    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramBotScopes $scope
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @param TelegramBotMessages $userMessage
     * @param TelegramBotMessages $botRequestMessage
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function checkAndProcessAnswer(TelegramWebhooks $webhook,
                                          TelegramBots $bot,
                                          TelegramBotScopes $scope,
                                          TelegramUsers $telegramUser,
                                          TelegramBotEvents $event,
                                          TelegramBotMessages $userMessage,
                                          TelegramBotMessages $botRequestMessage)
    {
        TelegramModule::setLanguageLocale($event->from_language_code);

        /*
         * Search user
         */
        $adminRole = \DB::table('roles')
            ->where('name', 'root')
            ->first();

        if (null == $adminRole) {
            return response('ok');
        }
        $searchUser = User::where('login', trim($userMessage->message))
            ->orWhere('email', trim($userMessage->message))
            ->first();
        $authorized = false;

        if (null !== $searchUser) {
            $authorized = \DB::table('model_has_roles')
                    ->where('role_id', $adminRole->id)
                    ->where('model_id', $searchUser->id)
                    ->where('model_type', 'App\Models\User')
                    ->count() > 0;
        }

        if (null === $searchUser || true !== $authorized) {
            /*
             * Error
             */
            $message = view('telegram.notification_bot.auth.login.user_answer_fails', [
                'webhook'      => $webhook,
                'bot'          => $bot,
                'scope'        => $scope,
                'telegramUser' => $telegramUser,
                'userMessage'  => $userMessage,
            ])->render();

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

        /*
         * Get and save info
         */
        $telegramUser->auth_check_login = trim($userMessage->message);
        $telegramUser->save();

        /*
         * Success
         */
        $message = view('telegram.notification_bot.auth.login.user_answer_success', [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'scope'        => $scope,
            'telegramUser' => $telegramUser,
            'userMessage'  => $userMessage,
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

        TelegramBotMessages::closeUserScopes($event, $bot);

        app()->call(NotificationBotEnterPasswordController::class.'@index', [
            'webhook'       => $webhook,
            'bot'           => $bot,
            'scope'         => $scope,
            'telegramUser'  => $telegramUser,
            'event'         => $event,
        ]);

        return response('ok');
    }
}
