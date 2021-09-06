<?php

namespace App\Modules\PaymentSystems;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;

/**
 * Class CloudPaymentsModule
 * @package App\Modules\PaymentSystems
 */
class CloudPaymentsModule
{
    /**
     * @param Transaction $transaction
     * @return array
     */
    public static function getTopupChargeData(Transaction $transaction)
    {
        /** @var User $user */
        $user = $transaction->user;
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';

        $options = [
            'publicId' => config('money.cloudpayments_public_id'),
            'description' => __('Add funds') . ' (' . $transaction->amount . ' ' . __($currency) . ')',
            'amount' => $transaction->amount,
            'currency' => $currency,
            'invoiceId' => $transaction->id,
            'accountId' => empty($user->email) ? $user->phone : $user->email,
            'skin' => 'classic'
        ];

        return [
            'options' => $options,
            'routeSuccess' => route('payment.success'),
            'routeFail' => route('payment.fail'),
            'language' => app()->getLocale() == 'en' ? 'en-US' : 'ru-RU'
        ];
    }

    /**
     * @param Transaction $transaction
     * @param Subscription $subscription
     * @return array
     */
    public static function getOrderSubscriptionChargeData(Transaction $transaction, Subscription $subscription)
    {
        /** @var User $user */
        $user = $transaction->user;
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';

        $options = [
            'publicId' => config('money.cloudpayments_public_id'),
            'description' => __('Subscription for service') . ': ' . ($currency == 'USD' ? $subscription->packet->name_en : $subscription->packet->name_ru),
            'amount' => $transaction->amount,
            'currency' => $currency,
            'invoiceId' => $transaction->id,
            'accountId' => empty($user->email) ? $user->phone : $user->email,
            'skin' => 'classic',
            'data' => [
                'cloudPayments' => [
                    'recurrent' => [
                        'interval' => 'Day',
                        'period' => 30
                    ]
                ]
            ]
        ];

        return [
            'options' => $options,
            'routeSuccess' => URL::previous(),
            'routeFail' => URL::previous(),
        ];
    }

    /**
     * @param Transaction $transaction
     * @param Subscription $subscription
     * @return array
     */
    public static function getPremiumAccountSubscriptionChargeData(Transaction $transaction, Subscription $subscription)
    {
        /** @var User $user */
        $user = $transaction->user;

        $options = [
            'publicId' => config('money.cloudpayments_public_id'),
            'description' => __('Subscription for premium account'),
            'amount' => $transaction->amount,
            'currency' => $transaction->currency->code == 'USD' ? 'USD' : 'RUB',
            'invoiceId' => $transaction->id,
            'accountId' => empty($user->email) ? $user->phone : $user->email,
            'skin' => 'classic',
            'data' => [
                'cloudPayments' => [
                    'recurrent' => [
                        'interval' => 'Day',
                        'period' => 30,
                        'amount' => config('prices.premium_account')[$subscription->currency->code],
                        'startDate' => now()->addDays(3)
                    ]
                ]
            ]
        ];

        return [
            'options' => $options,
            'routeSuccess' => URL::previous(),
            'routeFail' => URL::previous(),
        ];
    }

    /**
     * @param Subscription $subscription
     * @param array $response
     */
    public static function createPremiumAccountSubscription(Subscription $subscription, array $response)
    {
        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://api.cloudpayments.ru/subscriptions/create', [
            'auth' => [config('money.cloudpayments_public_id'), config('money.cloudpayments_api_secret')],
            'json' => [
                'token' => $response['Token'],
                'accountId' => $response['AccountId'],
                'description' => $response['Description'],
                'email' => $response['Email'],
                'amount' => config('prices.premium_account')[$subscription->currency->code],
                'currency' => $subscription->currency->code == 'USD' ? 'USD' : 'RUB',
                'requireConfirmation' => false,
                'startDate' => now()->addDays(3)->format('Y-m-d H:m:s'),
                'interval' => 'Day',
                'period' => 30
            ]
        ]);

        $result = json_decode($response->getBody()->getContents());
        if ($result->Success) {
            $subscription->subscription_id = $result->Model->Id;
            $subscription->status = $result->Model->Status;
            $subscription->date_at = strtotime($result->Model->NextTransactionDateIso);
            $subscription->save();
            if ($subscription->status == 'Active') {
                $subscription->user->updatePremiumStatus(true);
            }
        } else {
            $subscription->status = 'Error';
            $subscription->note = json_encode($result);
            $subscription->save();
        }
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     */
    public static function getSubscriptionDetails(Subscription $subscription)
    {
        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://api.cloudpayments.ru/subscriptions/get', [
            'auth' => [config('money.cloudpayments_public_id'), config('money.cloudpayments_api_secret')],
            'json' => ['Id' => $subscription->subscription_id]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     * @throws \Exception
     */
    public static function updateSubscription(Subscription $subscription)
    {
        if ($subscription->type == 'PremiumAccount') {
            $amount = config('prices.premium_account')[$subscription->currency->code];
            $currency = $subscription->currency->code == 'USD' ? 'USD' : 'RUB';
        } else {
            /** @var Wallet $wallet */
            $wallet = $subscription->user->getActiveWallet();
            $amount = $wallet->apply_discount($subscription->packet->price * $subscription->posts * $subscription->qty_max);
            if ($subscription->currency->code == 'USD') {
                $amount = convertRubToUsd($amount);
                $currency = 'USD';
            } else {
                $currency = 'RUB';
            }
        }

        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://api.cloudpayments.ru/subscriptions/update', [
            'auth' => [config('money.cloudpayments_public_id'), config('money.cloudpayments_api_secret')],
            'json' => [
                'Id' => $subscription->subscription_id,
                'Amount' => $amount,
                'Currency' => $currency,
                'Interval' => 'Day',
                'Period' => $subscription->period
            ]
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->Success) {
            return $result;
        } else {
            throw new \Exception(json_encode($result));
        }
    }

    /**
     * @param Subscription $subscription
     * @throws \Exception
     */
    public static function closeSubscription(Subscription $subscription)
    {
        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://api.cloudpayments.ru/subscriptions/cancel', [
            'auth' => [config('money.cloudpayments_public_id'), config('money.cloudpayments_api_secret')],
            'json' => ['Id' => $subscription->subscription_id]
        ]);
        $result = json_decode($response->getBody()->getContents());
        if ($result->Success) {
            $subscription->status = 'Cancelled';
            $subscription->save();
            if ($subscription->type == 'PremiumAccount') {
                $subscription->user->updatePremiumStatus(false);
            }
        } else {
            $subscription->note = json_encode($result);
            $subscription->save();
            throw new \Exception('Something went wrong. Please try again');
        }
    }
}
