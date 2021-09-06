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

class NotificationBotEnterPasswordController extends Controller
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
            ->where('command', '/enter_password')
            ->first();

        TelegramModule::setLanguageLocale($event->from_language_code);
        $message = view('telegram.notification_bot.auth.password.index', [
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

        /** @var User $searchUser */
        $searchUser = User::where(function($query) use ($telegramUser) {
            return $query->where('login', $telegramUser->auth_check_login)
                ->orWhere('email', $telegramUser->auth_check_login);
        })
            ->first();

        if (false === password_verify($userMessage->message, $searchUser->password)) {
            $searchUser = null;
        }

        $authorized = false;

        if (null !== $searchUser) {
            $authorized = \DB::table('model_has_roles')
                    ->where('role_id', $adminRole->id)
                    ->where('model_id', $searchUser->id)
                    ->where('model_type', 'App\Models\User')
                    ->count() > 0;
        }

        if ($searchUser === null || true !== $authorized) {
            /*
             * Error
             */
            $message = view('telegram.notification_bot.auth.password.user_answer_fails', [
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

            $telegramUser->auth_check_login = null;
            $telegramUser->auth_check_name  = null;
            $telegramUser->save();

            app()->call(NotificationBotAuthController::class.'@index', [
                'webhook'       => $webhook,
                'bot'           => $bot,
                'scope'         => $scope,
                'telegramUser'  => $telegramUser,
                'event'         => $event,
            ]);

            return response('ok');
        }

        /*
         * Get and save info
         */
        $oldUser = $telegramUser->user()->first();

        $telegramUser->auth_check_login = $searchUser->login;
        $telegramUser->user_id          = $searchUser->id;
        $telegramUser->save();

        $oldUser->delete();

        /*
         * Success
         */
        $message = view('telegram.notification_bot.auth.password.user_answer_success', [
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

        app()->call(NotificationBotStartController::class.'@index', [
            'webhook'       => $webhook,
            'bot'           => $bot,
            'scope'         => $scope,
            'telegramUser'  => $telegramUser,
            'event'         => $event,
        ]);

        return response('ok');
    }
}
