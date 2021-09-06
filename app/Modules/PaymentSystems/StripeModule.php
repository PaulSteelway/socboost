<?php

namespace App\Modules\PaymentSystems;

use App\Models\Transaction;
use GuzzleHttp\Client;

/**
 * Class PayOpModule
 * @package App\Modules\PaymentSystems
 */
class StripeModule
{
    /**
     * @param Transaction $transaction
     * @param null|string $resultUrl
     * @param null|string $failPath
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createTopupInvoice(Transaction $transaction, $resultUrl = null, $failPath = null)
    {
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        $locale = app()->getLocale();

        // $json = [
        //     'publicKey' => config('money.stripe_api_key'),
        //     'order' => [
        //         'id' => $transaction->id,
        //         'amount' => $transaction->amount,
        //         'currency' => $currency,
        //         'items' => [],
        //         'description' => __('Add funds') . ' (' . $transaction->amount . ' ' . __($currency) . ')'
        //     ],
        //     'payer' => [
        //         'email' => $transaction->user->email,
        //         'phone' => '',
        //         'name' => '',
        //         'extraFields' => []
        //     ],

        //     'language' => $locale,
        //     'resultUrl' => empty($resultUrl) ? route('payment.success') : $resultUrl,
        //     'failPath' => empty($failPath) ? route('payment.fail') : $failPath,
        //     'signature' => self::getSignature(['id' => $transaction->id, 'amount' => $transaction->amount, 'currency' => $currency]),
        // ];


        \Stripe\Stripe::setApiKey(config('money.stripe_api_key'));

        header('Content-Type: application/json');

        //$YOUR_DOMAIN = 'http://localhost:4242'; // ?!?!?

        $amount = ceil($transaction->amount*100);


        if($locale == 'en') {
          $desc = 'Add funds to balance';
        }
        else {
          $desc = 'Пополнение баланса';
        }
        
        $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
            'price_data' => [
              'currency' => strtolower($currency),
              'unit_amount' => $amount,
              'product_data' => [
                'name' => $desc,
                // 'images' => ["https://i.imgur.com/EHyR2nP.png"],
              ],
            ],
            'quantity' => 1,
          ]
        ],
        'mode' => 'payment',
        'success_url' => route('payment.success'),
        'cancel_url' => route('payment.fail'),
        'metadata'  => [
          'transaction_id' => $transaction->id
        ]
        ]);
        error_log("TRANSACTION");
        error_log($transaction->id);

        return $checkout_session->id;
    }
}
