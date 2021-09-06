<?php

namespace App\Http\Managers;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Modules\PaymentSystems\CloudPaymentsModule;

class CloudPaymentsManager
{
    /**
     * @param Transaction $transaction
     * @param array $response
     * @throws \Throwable
     */
    public static function processSuccessTopupResponse(Transaction $transaction, array $response)
    {
        $transaction->batch_id = $response['TransactionId'];
        $transaction->result = $response['Status'];
        $transaction->status = 1;
        $transaction->save();

        $commission = $transaction->amount * 0.01 * $transaction->commission;
        $transactionAmount = $transaction->amount - $commission;
        if ($transaction->currency->code == 'USD') {
            $transactionAmount = convertUsdToRub($transactionAmount);
        }
        $transactionAmount = PaymentManager::checkTransactionForPromocode($transaction, $transactionAmount);
        $transaction->wallet->refill($transactionAmount, $transaction->source);
        $transaction->approved = true;
        $transaction->save();

        UserManager::accrueReferralBonusForEnter($transaction, $transactionAmount);
        UserManager::accrueBonusWithTopup($transaction);
    }

    /**
     * @param Transaction $transaction
     * @param array $response
     */
    public static function processFailTopupResponse(Transaction $transaction, array $response)
    {
        $transaction->batch_id = $response['TransactionId'];
        $transaction->result = "{$response['Status']}: {$response['Reason']}";
        $transaction->status = 1;
        $transaction->save();
    }

    /**
     * @param Transaction $transaction
     * @param array $response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function processSuccessSubscriptionInit(Transaction $transaction, array $response)
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::find($transaction->source);
        if ($subscription->type == 'PremiumAccount') {
            if (empty($transaction->approved)) {
                $transaction->batch_id = $response['TransactionId'];
                $transaction->result = 'Completed';
                $transaction->approved = true;
                $transaction->status = 1;
                $transaction->save();
                CloudPaymentsModule::createPremiumAccountSubscription($subscription, $response);
            }
        } else {
            if (empty($response['SubscriptionId'])) {
                $subscription->status = 'Error';
                $subscription->note = json_encode($response);
                $subscription->save();

                $transaction->batch_id = $response['TransactionId'];
                $transaction->result = 'SubscriptionId is empty';
                $transaction->status = 1;
                $transaction->save();
            } else {
                self::createOrderBySubscription($subscription, $transaction, $response);
            }
        }
    }

    /**
     * @param array $response
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public static function processSuccessSubscriptionRecurrent(array $response)
    {
        $subscription = Subscription::where('subscription_id', $response['SubscriptionId'])->first();
        if ($subscription instanceof Subscription) {
            if ($subscription->type == 'PremiumAccount') {
                /** @var Transaction $transaction */
                $transaction = Transaction::subscriptionPremiumAccount($subscription, 'cloudpayments', $response['Amount']);
                $transaction->batch_id = $response['TransactionId'];
                $transaction->result = 'Completed';
                $transaction->approved = true;
                $transaction->save();

                $result = CloudPaymentsModule::getSubscriptionDetails($subscription);
                if ($result->Success) {
                    $subscription->status = $result->Model->Status;
                    $subscription->date_at = strtotime($result->Model->NextTransactionDateIso);
                    $subscription->save();
                    if ($subscription->status == 'Active') {
                        $subscription->user->updatePremiumStatus(true);
                    }
                }
            } else {
                /** @var Transaction $transaction */
                $transaction = Transaction::subscriptionOrder($subscription, 'cloudpayments');
                self::createOrderBySubscription($subscription, $transaction, $response);
            }
        }
    }

    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @param array $response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createOrderBySubscription(Subscription $subscription, Transaction $transaction, array $response)
    {
        $subscription->date_at = now()->addDays($subscription->period);
        $subscription->subscription_id = $response['SubscriptionId'];
        $subscription->save();

        if (empty($transaction->approved)) {
            $transaction->batch_id = $response['TransactionId'];
            $transaction->result = 'Completed';
            $transaction->approved = true;
            $transaction->save();

            OrderManager::createSubscriptionCloudpaymentsOrder($subscription, $transaction);
        }
    }

    /**
     * @param Transaction $transaction
     * @param array $response
     */
    public static function processFailSubscriptionInit(Transaction $transaction, array $response)
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::find($transaction->source);
        $subscription->subscription_id = $response['SubscriptionId'];
        $subscription->status = 'Error';
        $subscription->note = json_encode($response);
        $subscription->save();

        $transaction->batch_id = $response['TransactionId'];
        $transaction->result = "{$response['ReasonCode']}: {$response['Reason']}";
        $transaction->save();
    }

    /**
     * @param array $response
     */
    public static function processFailSubscriptionRecurrent(array $response)
    {
        $subscription = Subscription::where('subscription_id', $response['SubscriptionId'])->first();
        if ($subscription instanceof Subscription) {
            $subscription->status = 'Error';
            $subscription->note = json_encode($response);
            $subscription->save();

            if ($subscription->type == 'PremiumAccount') {
                $subscription->user->updatePremiumStatus(false);
            }
        }
    }
}
