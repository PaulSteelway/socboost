<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\PaymentSystems;

use App\Models\PaymentSystem;
use App\Models\Transaction;

/**
 * Class EnpayModule
 * @package App\Modules\PaymentSystems
 */
class EnpayModule
{
    /**
     * @return array
     */
    public static function getBalances(): array
    {
        $ps       = PaymentSystem::getByCode('enpay');
        $balances = [];

        foreach ($ps->currencies as $currency) {
            $balances[$currency->code] = 0;
        }

        $ps->update([
            'external_balances' => json_encode($balances),
            'connected' => true,
        ]);

        return $balances;
    }

    /**
     * @param string $currency
     * @return float
     */
    public static function getBalance(string $currency): float
    {
        $balances = self::getBalances();
        return key_exists($currency, $balances) ? $balances[$currency] : 0;
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws \Exception
     */
    public static function transfer(Transaction $transaction
    ) {
        throw new \Exception('Enpay do not support API transfers. Please, handle this operation manually.');
    }
}