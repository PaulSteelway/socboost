<?php

namespace App\Modules\PaymentSystems;

use App\Models\Transaction;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

/**
 * Class PayPalModule
 * @package App\Modules\PaymentSystems
 */
class PayPalModule
{
    /**
     * @return PayPalHttpClient
     */
    private static function getClient()
    {
        if (config('app')['env'] == 'production') {
            $environment = new ProductionEnvironment(config('money')['paypal_client_id'], config('money')['paypal_secret']);
        } else {
            $environment = new SandboxEnvironment(config('money')['paypal_client_id'], config('money')['paypal_secret']);
        }

        return new PayPalHttpClient($environment);
    }


    public static function createTopupTransaction(Transaction $transaction)
    {

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'description' => 'social_booster',
                'custom_id' => $transaction->id,
                'amount' => [
                    'value' => $transaction->amount,
                    'currency_code' => $transaction->currency->code == 'RUR' ? 'RUB' : 'USD'
                ]
            ]],
            'application_context' => [
                'cancel_url' => route('paypal.cancel'),
                'return_url' => route('paypal.success')
            ]
        ];

        $client = self::getClient();

        $response = $client->execute($request);

        return $response;
    }

    /**
     * @param $transactionId
     * @return \PayPalHttp\HttpResponse
     */
    public static function captureTransaction($transactionId)
    {
        $client = self::getClient();

        $request = new OrdersCaptureRequest($transactionId);

        $response = $client->execute($request);

        return $response;
    }
}