<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooks;
use App\Modules\Messangers\TelegramModule;

class TelegramNoRefRegistrationResponseController extends Controller
{
    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param array $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function index(TelegramWebhooks $webhook,
                          TelegramBots $bot,
                          array $event)
    {
        TelegramModule::setLanguageLocale($event['from_language_code']);
        $message = view('telegram.no_ref_registration_response', [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'event'        => $event,
        ])->render();

        if (config('app.env') == 'develop') {
            \Log::info('Prepared VIEW and message for bot:<hr>'.$message);
        }

        try {
            $telegramInstance = new TelegramModule($bot->keyword);
            $telegramInstance->sendMessage($event['chat_id'], $message, 'HTML');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response('ok');
        }

        return response('ok');
    }
}