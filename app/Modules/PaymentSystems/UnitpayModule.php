<?php

namespace App\Modules\PaymentSystems;

use App\Http\Requests\ReferralPayoutRequest;
use App\Http\Resources\ReferralPayoutResource;
use App\Models\Subscription;
use App\Models\Transaction;
use GuzzleHttp\Client;
use App\Modules\API\UnitPay;

/**
 * Class UnitpayModule
 * @package App\Modules\PaymentSystems
 */
class UnitpayModule
{
    /**
     * @return UnitPay
     */
    private static function getClient($secretKey = 'money.unitpay_secret_key', $domain = 'money.unitpay_domain')
    {
      if (isFreePromotionSite()) {
        $secretKey = 'money.unitpay_secret_key_free';
      }
      return new UnitPay(config($domain), config($secretKey));
    }

    /**
     * @return Client
     */
    private static function getGuzzleClient()
    {
        return new Client(['base_uri' => 'https://unitpay.ru/api']);
    }

    /**
     * @param Transaction $transaction
     * @return object
     */
    public static function createTopupTransaction(Transaction $transaction)
    {
        $client = self::getClient();

        $publicKey = config('money.unitpay_public_key');
        if (isFreePromotionSite()) {
            $publicKey = config('money.unitpay_public_key_free');
        }

        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';

        return $client->api('initPayment', [
            'account' => $transaction->id,
            'desc' => "Add funds ({$transaction->amount} {$currency})",
            'sum' => $transaction->amount,
            'paymentType' => 'card',
            'currency' => $currency,
            'projectId' => $publicKey
        ]);
    }

    /**
     * @param $method
     * @param array $params
     * @return string
     */
    public static function getSignature($method, array $params)
    {
        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
          $secretKey = config('money.unitpay_secret_key_free');
        }

        ksort($params);
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, $secretKey);
        array_unshift($params, $method);
        return hash('sha256', join('{up}', $params));
    }

    /**
     * @param Transaction $transaction
     * @return string
     */
    public static function gerRedirectPathForPay(Transaction $transaction)
    {

        $publicKey = config('money.unitpay_public_key');
        if (isFreePromotionSite()) {
            $publicKey = config('money.unitpay_public_key_free');
        }

        $account = $transaction->id;
        $sum = $transaction->amount;
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        $desc = "Add funds ({$sum} {$currency})";
        $signature = self::getFormSignature($account, $currency, $desc, $sum);
        $customerEmail = $transaction->user->email;
        return "https://unitpay.ru/pay/{$publicKey}?sum={$sum}&account={$account}&desc={$desc}&currency={$currency}&signature={$signature}&customerEmail={$customerEmail}";
    }

    /**
     * @param Transaction $transaction
     * @param array $additional_fields
     * @return array
     */
    public static function getUnitpayWidgetData(Transaction $transaction, array $additional_fields=[])
    {
        if ($additional_fields) {
            $additional_fields['account'] = $transaction->id;
            $account = json_encode($additional_fields);
            $success_url = route('unitpay.success', ['success'=>'order']);
        }else {
            $account = $transaction->id;
            $success_url = route('unitpay.success', ['success'=>'topup']);
        }
        $sum = $transaction->amount;
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        $desc = "Add funds ({$sum} {$currency})";
        $signature = self::getFormSignature($account, $currency, $desc, $sum);

        $publicKey = config('money.unitpay_public_key');
        if (isFreePromotionSite()) {
            $publicKey = config('money.unitpay_public_key_free');
        }

        return [
            'resultUrl' => $success_url,
            'publicKey' => $publicKey,
            'sum' => $sum,
            'account' => $account,
            'domainName' => 'unitpay.money',
            'signature' => $signature,
            'desc' => $desc,
            'locale' => app()->getLocale(),
            'currency' => $currency
        ];
    }

    /**
     * @param Transaction $transaction
     * @return string
     */
    public static function gerRedirectPathForTestPackagePay($test_package, $currency_model, $order_link)
    {
        $publicKey = config('money.unitpay_public_key');
        if (isFreePromotionSite()) {
            $publicKey = config('money.unitpay_public_key_free');
        }

        $account['test_package_id'] = $test_package->id;
        $account['order_link'] = $order_link;
        $encoded_account = json_encode($account);
        $sum = $test_package->price;
        $currency = $currency_model->code == 'USD' ? 'USD' : 'RUB';
        $desc = "Test package purchase {$test_package->name} ({$sum} {$currency})";
        $signature = self::getFormSignature($encoded_account, $currency, $desc, $sum);
        return "https://unitpay.ru/pay/{$publicKey}?sum={$sum}&account={$encoded_account}&desc={$desc}&currency={$currency}&signature={$signature}&orderLink={$order_link}";
    }

    /**
     * @param $test_package
     * @param $currency_model
     * @param $order_link
     * @return array
     */
    public static function gerUnitpayWidgetDataForTestPackagePay($test_package, $currency_model, $order_link)
    {
        $account['test_package_id'] = $test_package->id;
        $account['order_link'] = $order_link;
        $encoded_account = json_encode($account);
        $sum = $test_package->price;
        $currency = $currency_model->code == 'USD' ? 'USD' : 'RUB';
        $desc = "Test package purchase {$test_package->name} ({$sum} {$currency})";
        $signature = self::getFormSignature($encoded_account, $currency, $desc, $sum);

        $publicKey = config('money.unitpay_public_key');
        if (isFreePromotionSite()) {
            $publicKey = config('money.unitpay_public_key_free');
        }

        return [
            'publicKey' => $publicKey,
            'sum' => $sum,
            'account' => $encoded_account,
            'domainName' => 'unitpay.money',
            'signature' => $signature,
            'desc' => $desc,
            'locale' => app()->getLocale(),
            'currency' => $currency
        ];
    }

    /**
     * @param $account
     * @param $currency
     * @param $desc
     * @param $sum
     * @return string
     */
    public static function getFormSignature($account, $currency, $desc, $sum)
    {
        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $secretKey = config('money.unitpay_secret_key_free');
        }

        $hashStr = $account . '{up}' . $currency . '{up}' . $desc . '{up}' . $sum . '{up}' . $secretKey;
        return hash('sha256', $hashStr);
    }

    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @return string
     * @throws \Exception
     */
    public static function sendSubscriptionInitRequest(Subscription $subscription, Transaction $transaction)
    {
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        if ($subscription->type == 'PremiumAccount') {
            $desc = __('Subscription for premium account');
        } else {
            $desc = __('Subscription for service') . ': ' . ($currency == 'USD' ? $subscription->packet->name_en : $subscription->packet->name_ru);
        }
        $success_url = $subscription->type == 'PremiumAccount' ? route('unitpay.success', ['success'=>'premium_subscription']) : route('unitpay.success', ['success'=>'other_subscription']);
        $projectId = config('money.unitpay_project_id');
        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $projectId = config('money.unitpay_project_id_free');
            $secretKey = config('money.unitpay_secret_key_free');
        }

        $params = [
            'paymentType' => $subscription->payment_method,
            'account' => $transaction->id,
            'sum' => $transaction->amount,
            'projectId' => $projectId,
            'resultUrl' => $success_url,
            'desc' => $desc,
            'ip' => $subscription->ip,
            'secretKey' => $secretKey,
            'currency' => $currency,
            'signature' => $signature = self::getFormSignature($transaction->id, $currency, $desc, $transaction->amount),
            'subscription' => true
        ];

        if (strcasecmp(config('app.env'), 'production') != 0) {
            $params['login'] = 'vnitro@Blacksmog.icu';
            $params['paymentType'] = 'card';
            $params['currency'] = 'RUB';
            $params['test'] = 1;
        }

        $client = self::getClient();
        $result = $client->api('initPayment', $params);
        if (!empty($result->error->message)) {
            $subscription->status = 'error';
            $subscription->note = $result->error->message;
            $subscription->save();

            $transaction->result = $result->error->message;
            $transaction->save();
            throw new \Exception($result->error->message);
        }

        $transaction->batch_id = $result->result->paymentId;
        $transaction->save();

        return $result->result->redirectUrl;
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     */
    public static function getSubscriptionDetails(Subscription $subscription)
    {

        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $secretKey = config('money.unitpay_secret_key_free');
        }

        $params = [
            'subscriptionId' => $subscription->subscription_id,
            'secretKey' => $secretKey
        ];

        if (strcasecmp(config('app.env'), 'production') != 0) {
            $params['test'] = 1;
        }
        $client = self::getClient();
        $result = $client->api('getSubscription', $params);
        return $result;
    }

    /**
     * @param Subscription $subscription
     * @throws \Exception
     */
    public static function closeSubscription(Subscription $subscription)
    {
        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $secretKey = config('money.unitpay_secret_key_free');
        }

        $params = [
            'subscriptionId' => $subscription->subscription_id,
            'secretKey' => $secretKey
        ];

        if (strcasecmp(config('app.env'), 'production') != 0) {
            $params['test'] = 1;
        }


        $client = self::getClient();
        $result = $client->api('closeSubscription', $params);
        if (!empty($result->error)) {
            $subscription->note = $result->error->message;
            $subscription->save();
            throw new \Exception($result->error->message);
        } else {
            $subscription->status = 'close';
            $subscription->save();
        }
    }

    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @return object
     */
    public static function makeSubscriptionTransaction(Subscription $subscription, Transaction $transaction)
    {
        $currency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        if ($subscription->type == 'PremiumAccount') {
            $desc = __('Subscription for premium account');
        } else {
            $desc = __('Subscription for service') . ': ' . ($currency == 'USD' ? $subscription->packet->name_en : $subscription->packet->name_ru);
        }

        $projectId = config('money.unitpay_project_id');
        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $projectId = config('money.unitpay_project_id_free');
            $secretKey = config('money.unitpay_secret_key_free');
        }
        $success_url = $subscription->type == 'PremiumAccount' ? route('unitpay.success', ['success'=>'premium_subscription']) : route('unitpay.success', ['success'=>'other_subscription']);
        $params = [
            'paymentType' => $subscription->payment_method,
            'account' => $transaction->id,
            'sum' => $transaction->amount,
            'projectId' => $projectId,
            'resultUrl' => $success_url,
            'desc' => $desc,
            'ip' => $subscription->ip,
            'secretKey' => $secretKey,
            'currency' => $currency,
            'signature' => $signature = self::getFormSignature($transaction->id, $currency, $desc, $transaction->amount),
            'subscriptionId' => $subscription->subscription_id
        ];

        if (strcasecmp(config('app.env'), 'production') != 0) {
            $params['login'] = 'vnitro@Blacksmog.icu';
            $params['paymentType'] = 'card';
            $params['currency'] = 'RUB';
            $params['test'] = 1;
        }

        $client = self::getClient();
        $result = $client->api('initPayment', $params);
        return $result;
    }

    /**
     * @param ReferralPayoutRequest $request
     * @return object
     */
    public static function referralPayout(ReferralPayoutRequest $request, Transaction $transaction)
    {
        $amount = $request->amount;
        $params = [
            'sum' => $amount,
            'purse' => $request->cardNumber,
            'login' => 'vnitrosmog@gmail.com',
            'transactionId' => $transaction->id,
            'secretKey' => config('money.unitpay_secret_key_personal'),
            'paymentType' => $request->cardType == 'Visa' ? 'card' : 'mc',
//            'test' => env('APP_ENV', 'production') === 'production' ? 0 : 1,
        ];

        $client = self::getClient('money.unitpay_secret_key_personal', 'money.unitpay_domain');
        return $client->api('massPayment', $params);
    }

}
