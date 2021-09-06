<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Currency;

/**
 * Class CurrencyObserver
 * @package App\Observers
 */
class CurrencyObserver
{
    /**
     * @param Currency $currency
     */
    public function deleting(Currency $currency)
    {
        foreach ($currency->deposits()->get() as $deposit) {
            $deposit->delete();
        }

        foreach ($currency->tasks()->get() as $task) {
            $task->delete();
        }

        foreach ($currency->transactions()->get() as $transaction) {
            $transaction->delete();
        }

        foreach ($currency->rates()->get() as $rate) {
            $rate->delete();
        }

        foreach ($currency->wallets()->get() as $wallet) {
            $wallet->delete();
        }
    }

    /**
     * Listen to the Currency created event.
     *
     * @param Currency $currency
     * @return void
     * @throws
     */
    public function created(Currency $currency)
    {
        clearCacheByArray($this->getCacheKeys($currency));
        clearCacheByTags($this->getCacheTags($currency));
    }

    /**
     * @param Currency $currency
     * @return array
     */
    private function getCacheKeys(Currency $currency): array
    {
        return [
            'i.currencies'
        ];
    }

    /**
     * @param Currency $currency
     * @return array
     */
    private function getCacheTags(Currency $currency): array
    {
        return [
            'currency',
            'precision',
        ];
    }

    /**
     * Listen to the Currency deleting event.
     *
     * @param Currency $currency
     * @return void
     * @throws
     */
    public function deleted(Currency $currency)
    {
        clearCacheByArray($this->getCacheKeys($currency));
        clearCacheByTags($this->getCacheTags($currency));
    }

    /**
     * Listen to the Currency updating event.
     *
     * @param Currency $currency
     * @return void
     * @throws
     */
    public function updated(Currency $currency)
    {
        clearCacheByArray($this->getCacheKeys($currency));
        clearCacheByTags($this->getCacheTags($currency));
    }
}