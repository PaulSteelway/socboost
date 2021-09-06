<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramWebhooks;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Modules\Messangers\TelegramModule;

/**
 * Class TelegramWebhooksObserver
 * @package App\Observers\Telegram
 */
class TelegramWebhooksObserver
{
    /**
     * @param TelegramWebhooks $webhook
     * @throws \Exception
     */
    public function deleting(TelegramWebhooks $webhook)
    {
        foreach($webhook->webhook_info()->get() as $webhookInfo) {
            $webhookInfo->delete();
        }
    }

    /**
     * Listen to the TelegramWebhooks created event.
     *
     * @param TelegramWebhooks $webhook
     * @return void
     * @throws
     */
    public function created(TelegramWebhooks $webhook)
    {
        clearCacheByArray($this->getCacheKeys($webhook));
        clearCacheByTags($this->getCacheTags($webhook));
    }

    /**
     * @param TelegramWebhooks $webhook
     * @return array
     */
    private function getCacheKeys(TelegramWebhooks $webhook): array
    {
        return [];
    }

    /**
     * @param TelegramWebhooks $webhook
     * @return array
     */
    private function getCacheTags(TelegramWebhooks $webhook): array
    {
        return [];
    }

    /**
     * Listen to the TelegramWebhooks deleting event.
     *
     * @param TelegramWebhooks $webhook
     * @return void
     * @throws
     */
    public function deleted(TelegramWebhooks $webhook)
    {
        clearCacheByArray($this->getCacheKeys($webhook));
        clearCacheByTags($this->getCacheTags($webhook));
    }

    /**
     * Listen to the TelegramWebhooks updating event.
     *
     * @param TelegramWebhooks $webhook
     * @return void
     * @throws
     */
    public function updated(TelegramWebhooks $webhook)
    {
        clearCacheByArray($this->getCacheKeys($webhook));
        clearCacheByTags($this->getCacheTags($webhook));
    }
}