<?php

namespace App\Modules\PaymentSystems;

use App\Models\Transaction;
use GuzzleHttp\Client;

/**
 * Class PayOpModule
 * @package App\Modules\PaymentSystems
 */
class PayOpModule
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

        $json = [
            'publicKey' => config('money.payop_public_key'),
            'order' => [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'currency' => $currency,
                'items' => [],
                'description' => __('Add funds') . ' (' . $transaction->amount . ' ' . __($currency) . ')'
            ],
            'payer' => [
                'email' => $transaction->user->email,
                'phone' => '',
                'name' => '',
                'extraFields' => []
            ],

            'language' => $locale,
            'resultUrl' => empty($resultUrl) ? route('payment.success') : $resultUrl,
            'failPath' => empty($failPath) ? route('payment.fail') : $failPath,
            'signature' => self::getSignature(['id' => $transaction->id, 'amount' => $transaction->amount, 'currency' => $currency]),
        ];

        $client = new Client();
        $response = $client->request('POST', 'https://payop.com/v1/invoices/create', ['json' => $json]);
        $identifier = $response->getHeader('identifier')[0];
        return "https://payop.com/{$locale}/payment/invoice-preprocessing/{$identifier}";
    }

    /**
     * @param array $order
     * @return string
     */
    private static function getSignature(array $order)
    {
        ksort($order, SORT_STRING);
        $dataSet = array_values($order);
        $dataSet[] = config('money.payop_secret_key');
        return hash('sha256', implode(':', $dataSet));
    }
}
