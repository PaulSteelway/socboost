<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Deposit;

/**
 * Class DepositObserver
 * @package App\Observers
 */
class DepositObserver
{
    /**
     * @param Deposit $deposit
     */
    public function deleting(Deposit $deposit)
    {
        foreach ($deposit->transactions()->get() as $transaction) {
            $transaction->delete();
        }
    }

    /**
     * @param Deposit $deposit
     * @return array
     */
    private function getCacheKeys(Deposit $deposit): array
    {
        if (null == $deposit->user_id) {
            return [];
        }

        return [];
    }

    /**
     * @param Deposit $deposit
     * @return array
     */
    private function getCacheTags(Deposit $deposit): array
    {
        if (null == $deposit->user_id) {
            return [];
        }

        return [
            'userDeposits.' . $deposit->user_id,
            'lastCreatedDeposits',
        ];
    }

    /**
     * Listen to the Deposit created event.
     *
     * @param Deposit $deposit
     * @return void
     * @throws
     */
    public function created(Deposit $deposit)
    {
        clearCacheByArray($this->getCacheKeys($deposit));
        clearCacheByTags($this->getCacheTags($deposit));
    }

    /**
     * Listen to the Deposit deleting event.
     *
     * @param Deposit $deposit
     * @return void
     * @throws
     */
    public function deleted(Deposit $deposit)
    {
        clearCacheByArray($this->getCacheKeys($deposit));
        clearCacheByTags($this->getCacheTags($deposit));
    }

    /**
     * Listen to the Deposit updating event.
     *
     * @param Deposit $deposit
     * @return void
     * @throws
     */
    public function updated(Deposit $deposit)
    {
        clearCacheByArray($this->getCacheKeys($deposit));
        clearCacheByTags($this->getCacheTags($deposit));
    }
}