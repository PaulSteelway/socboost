<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\PaymentSystems;

use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\Wallet;
use Gr8devofficial\LaravelFreecassa\Merchant;

/**
 * Class FreeKassaModule
 * @package App\Modules\PaymentSystems
 */
class FreeKassaModule
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function getBalances(): array
    {
        $ps = PaymentSystem::getByCode('free-kassa');

        $FK = new Merchant();


        $balances      = ['RUB'=>$FK->getBalance()->root->balance];


        if (isset($balances) && count($balances) > 0 && !empty($ps)) {
            $ps->update([
                'external_balances' => json_encode($balances),
                'connected' => true,
            ]);
        } else {
            $ps->update([
                'external_balances' => json_encode([]),
                'connected' => false,
            ]);
            throw new \Exception('Balance is not reachable.');
        }

        return $balances;
    }

    /**
     * @param string $currency
     * @return float
     * @throws \Exception
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
        /** @var Wallet $wallet */
        throw new \Exception('FreeKassa do not support API transfers. Please, handle this operation manually.');
    }
}