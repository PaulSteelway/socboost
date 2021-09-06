<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram\admin_bot;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Modules\Messangers\TelegramModule;

class AdminBotBotStartController extends Controller
{
    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramBotScopes $scope
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function index(TelegramWebhooks $webhook,
                          TelegramBots $bot,
                          TelegramBotScopes $scope,
                          TelegramUsers $telegramUser,
                          TelegramBotEvents $event)
    {
        TelegramModule::setLanguageLocale($event->from_language_code);
        $message = view('telegram.admin_bot.start', [
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
            $telegramInstance->sendMessage($telegramUser->chat_id, $message, 'HTML');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response('ok');
        }

        return response('ok');
    }
}
