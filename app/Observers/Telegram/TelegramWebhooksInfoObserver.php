<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramWebhooksInfo;

/**
 * Class TelegramWebhooksInfoObserver
 * @package App\Observers\Telegram
 */
class TelegramWebhooksInfoObserver
{
    /**
     * Listen to the TelegramWebhooksInfo created event.
     *
     * @param TelegramWebhooksInfo $webhookInfo
     * @return void
     * @throws
     */
    public function created(TelegramWebhooksInfo $webhookInfo)
    {
        clearCacheByArray($this->getCacheKeys($webhookInfo));
        clearCacheByTags($this->getCacheTags($webhookInfo));
    }

    /**
     * @param TelegramWebhooksInfo $webhookInfo
     * @return array
     */
    private function getCacheKeys(TelegramWebhooksInfo $webhookInfo): array
    {
        return [];
    }

    /**
     * @param TelegramWebhooksInfo $webhookInfo
     * @return array
     */
    private function getCacheTags(TelegramWebhooksInfo $webhookInfo): array
    {
        return [];
    }

    /**
     * Listen to the TelegramWebhooksInfo deleting event.
     *
     * @param TelegramWebhooksInfo $webhookInfo
     * @return void
     * @throws
     */
    public function deleted(TelegramWebhooksInfo $webhookInfo)
    {
        clearCacheByArray($this->getCacheKeys($webhookInfo));
        clearCacheByTags($this->getCacheTags($webhookInfo));
    }

    /**
     * Listen to the TelegramWebhooksInfo updating event.
     *
     * @param TelegramWebhooksInfo $webhookInfo
     * @return void
     * @throws
     */
    public function updated(TelegramWebhooksInfo $webhookInfo)
    {
        clearCacheByArray($this->getCacheKeys($webhookInfo));
        clearCacheByTags($this->getCacheTags($webhookInfo));
    }
}