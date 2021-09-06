<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;

/**
 * Class TelegramBotsObserver
 * @package App\Observers\Telegram
 */
class TelegramBotsObserver
{
    /**
     * @param TelegramBots $bot
     * @throws \Exception
     */
    public function deleting(TelegramBots $bot)
    {
        foreach($bot->messages()->get() as $message) {
            $message->delete();
        }

        foreach($bot->webhooks()->get() as $webhook) {
            foreach($webhook->webhook_info()->get() as $info) {
                $info->delete();
            }
            $webhook->delete();
        }

        foreach (TelegramBotScopes::where('bot_keyword', $bot->keyword)->get() as $scope) {
            $scope->delete();
        }

        foreach ($bot->events()->get() as $event) {
            $event->delete();
        }

        foreach($bot->users()->get() as $user) {
            $user->delete();
        }
    }

    /**
     * Listen to the TelegramBots created event.
     *
     * @param TelegramBots $bot
     * @return void
     * @throws
     */
    public function created(TelegramBots $bot)
    {
        clearCacheByArray($this->getCacheKeys($bot));
        clearCacheByTags($this->getCacheTags($bot));
    }

    /**
     * @param TelegramBots $bot
     * @return array
     */
    private function getCacheKeys(TelegramBots $bot): array
    {
        return [
            'telegramBots'
        ];
    }

    /**
     * @param TelegramBots $bot
     * @return array
     */
    private function getCacheTags(TelegramBots $bot): array
    {
        return [];
    }

    /**
     * Listen to the TelegramBots deleting event.
     *
     * @param TelegramBots $bot
     * @return void
     * @throws
     */
    public function deleted(TelegramBots $bot)
    {
        clearCacheByArray($this->getCacheKeys($bot));
        clearCacheByTags($this->getCacheTags($bot));
    }

    /**
     * Listen to the TelegramBots updating event.
     *
     * @param TelegramBots $bot
     * @return void
     * @throws
     */
    public function updated(TelegramBots $bot)
    {
        clearCacheByArray($this->getCacheKeys($bot));
        clearCacheByTags($this->getCacheTags($bot));
    }
}