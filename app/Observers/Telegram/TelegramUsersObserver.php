<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers\Telegram;

use App\Models\Telegram\TelegramUsers;
use App\Models\User;
use App\Models\UsersSocialMeta;

/**
 * Class TelegramUsersObserver
 * @package App\Observers\Telegram
 */
class TelegramUsersObserver
{
    /**
     * Listen to the TelegramUsers created event.
     *
     * @param TelegramUsers $telegramUser
     * @return void
     * @throws
     */
    public function created(TelegramUsers $telegramUser)
    {
        /** @var User $user */
        $user = $telegramUser->user;

        for ($i = 1; $i <= 10; $i++) {
            /** @var User $partner */
            $partner = $user->getPartnerOnLevel($i);

            if (null === $partner) {
                break;
            }

            $notificationActive = UsersSocialMeta::getValue($user, 'settings_notifications_new_referral '.$i.'_level', 1);

            if (1 == $notificationActive) {
                $partner->sendNotification('new_partner', [
                    'level'         => $i,
                    'partner'       => $user,
                ]);
            }
        }

        clearCacheByArray($this->getCacheKeys($telegramUser));
        clearCacheByTags($this->getCacheTags($telegramUser));
    }

    /**
     * @param TelegramUsers $telegramUser
     * @return array
     */
    private function getCacheKeys(TelegramUsers $telegramUser): array
    {
        return [];
    }

    /**
     * @param TelegramUsers $telegramUser
     * @return array
     */
    private function getCacheTags(TelegramUsers $telegramUser): array
    {
        return [];
    }

    /**
     * Listen to the TelegramUsers deleting event.
     *
     * @param TelegramUsers $telegramUser
     * @return void
     * @throws
     */
    public function deleted(TelegramUsers $telegramUser)
    {
        clearCacheByArray($this->getCacheKeys($telegramUser));
        clearCacheByTags($this->getCacheTags($telegramUser));
    }

    /**
     * Listen to the TelegramUsers updating event.
     *
     * @param TelegramUsers $telegramUser
     * @return void
     * @throws
     */
    public function updated(TelegramUsers $telegramUser)
    {
        clearCacheByArray($this->getCacheKeys($telegramUser));
        clearCacheByTags($this->getCacheTags($telegramUser));
    }
}