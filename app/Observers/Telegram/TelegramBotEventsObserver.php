<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramBotEvents;

/**
 * Class TelegramBotEventsObserver
 * @package App\Observers\Telegram
 */
class TelegramBotEventsObserver
{
    /**
     * Listen to the TelegramBotEvents created event.
     *
     * @param TelegramBotEvents $event
     * @return void
     * @throws
     */
    public function created(TelegramBotEvents $event)
    {
        clearCacheByArray($this->getCacheKeys($event));
        clearCacheByTags($this->getCacheTags($event));
    }

    /**
     * @param TelegramBotEvents $event
     * @return array
     */
    private function getCacheKeys(TelegramBotEvents $event): array
    {
        return [];
    }

    /**
     * @param TelegramBotEvents $event
     * @return array
     */
    private function getCacheTags(TelegramBotEvents $event): array
    {
        return [];
    }

    /**
     * Listen to the TelegramBotEvents deleting event.
     *
     * @param TelegramBotEvents $event
     * @return void
     * @throws
     */
    public function deleted(TelegramBotEvents $event)
    {
        clearCacheByArray($this->getCacheKeys($event));
        clearCacheByTags($this->getCacheTags($event));
    }

    /**
     * Listen to the TelegramBotEvents updating event.
     *
     * @param TelegramBotEvents $event
     * @return void
     * @throws
     */
    public function updated(TelegramBotEvents $event)
    {
        clearCacheByArray($this->getCacheKeys($event));
        clearCacheByTags($this->getCacheTags($event));
    }
}