<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramBotMessages;

/**
 * Class TelegramBotMessagesObserver
 * @package App\Observers\Telegram
 */
class TelegramBotMessagesObserver
{
    /**
     * Listen to the TelegramBotMessages created event.
     *
     * @param TelegramBotMessages $message
     * @return void
     * @throws
     */
    public function created(TelegramBotMessages $message)
    {
        clearCacheByArray($this->getCacheKeys($message));
        clearCacheByTags($this->getCacheTags($message));
    }

    /**
     * @param TelegramBotMessages $message
     * @return array
     */
    private function getCacheKeys(TelegramBotMessages $message): array
    {
        return [];
    }

    /**
     * @param TelegramBotMessages $message
     * @return array
     */
    private function getCacheTags(TelegramBotMessages $message): array
    {
        return [];
    }

    /**
     * Listen to the TelegramBotMessages deleting event.
     *
     * @param TelegramBotMessages $message
     * @return void
     * @throws
     */
    public function deleted(TelegramBotMessages $message)
    {
        clearCacheByArray($this->getCacheKeys($message));
        clearCacheByTags($this->getCacheTags($message));
    }

    /**
     * Listen to the TelegramBotMessages updating event.
     *
     * @param TelegramBotMessages $message
     * @return void
     * @throws
     */
    public function updated(TelegramBotMessages $message)
    {
        clearCacheByArray($this->getCacheKeys($message));
        clearCacheByTags($this->getCacheTags($message));
    }
}