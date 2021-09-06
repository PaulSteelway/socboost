<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Modules\Messangers\TelegramModule;

class TelegramUserTimeAnswerWastedController extends Controller
{
    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @param TelegramBotMessages $lastRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function index(TelegramWebhooks $webhook,
                          TelegramBots $bot,
                          TelegramUsers $telegramUser,
                          TelegramBotEvents $event,
                          TelegramBotMessages $lastRequest)
    {
        TelegramModule::setLanguageLocale($event->from_language_code);
        $message = view('telegram.user_time_answer_wasted', [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'telegramUser' => $telegramUser,
            'lastRequest'  => $lastRequest,
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