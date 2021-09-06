<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramBotScopes;

/**
 * Class TelegramBotScopesObserver
 * @package App\Observers\Telegram
 */
class TelegramBotScopesObserver
{
    /**
     * Listen to the TelegramBotScopes created event.
     *
     * @param TelegramBotScopes $scope
     * @return void
     * @throws
     */
    public function created(TelegramBotScopes $scope)
    {
        clearCacheByArray($this->getCacheKeys($scope));
        clearCacheByTags($this->getCacheTags($scope));
    }

    /**
     * @param TelegramBotScopes $scope
     * @return array
     */
    private function getCacheKeys(TelegramBotScopes $scope): array
    {
        return [];
    }

    /**
     * @param TelegramBotScopes $scope
     * @return array
     */
    private function getCacheTags(TelegramBotScopes $scope): array
    {
        return [];
    }

    /**
     * Listen to the TelegramBotScopes deleting event.
     *
     * @param TelegramBotScopes $scope
     * @return void
     * @throws
     */
    public function deleted(TelegramBotScopes $scope)
    {
        clearCacheByArray($this->getCacheKeys($scope));
        clearCacheByTags($this->getCacheTags($scope));
    }

    /**
     * Listen to the TelegramBotScopes updating event.
     *
     * @param TelegramBotScopes $scope
     * @return void
     * @throws
     */
    public function updated(TelegramBotScopes $scope)
    {
        clearCacheByArray($this->getCacheKeys($scope));
        clearCacheByTags($this->getCacheTags($scope));
    }
}