<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\PaymentSystems;

use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;

use Coinbase\Wallet\Client;
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;


/**
 * Class CoinpaymentsModule
 * @package App\Modules\PaymentSystems
 */
class CoinbaseModule
{
    /**
     * @var Client
     */
    private $client;


    /**
     * CoinbaseModule constructor.
     */
    public function __construct()
    {

    }




    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws \Exception
     */
    public static function createTopupTransaction(Transaction $transaction, $metadata)
    {
        ApiClient::init(config('money.coinbase_api_key'));

        $chargeObj = new Charge(
            [
                'name' => 'The Social Booster',
                'description' => '',
                'local_price' => [
                    'amount' => $transaction->amount,
                    'currency' => 'RUB'
                ],
                'pricing_type' => 'fixed_price',
                'redirect_url' => config('money.coinbase_success_url'),
                'cancel_url' => config('money.coinbase_cancel_url'),
                'metadata' => $metadata
            ]
        );
        try {
            $chargeObj->save();
            echo sprintf("Successfully created new charge with id: %s \n", $chargeObj->id);
        } catch (\Exception $exception) {
            echo sprintf("Enable to create charge. Error: %s \n", $exception->getMessage());
        }

        return $chargeObj;
    }
}