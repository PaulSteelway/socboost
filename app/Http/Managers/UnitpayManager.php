<?php

namespace App\Http\Managers;

use App\Jobs\OrdersJapSubscriptionsJob;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UnitpayManager
{
    /**
     * @param Transaction $transaction
     * @param array $response
     * @throws \Throwable
     */
    public static function processTopupResponse(Transaction $transaction, array $response)
    {
        $transaction->batch_id = $response['unitpayId'];
        $transaction->result = 'Completed';
        $transaction->save();

        $commission = $transaction->amount * 0.01 * $transaction->commission;
        $transactionAmount = $transaction->amount - $commission;
        if ($transaction->currency->code == 'USD') {
            $transactionAmount = convertUsdToRub($transactionAmount);
        }
        if ($transaction->site_id == Transaction::FREE_BOOSTER_SITE) {
            $transactionAmount = convertRUBToScore($transactionAmount);
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
    public static function processSubscriptionResponse(Transaction $transaction, array $response)
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::find($transaction->source);
        if (empty($response['subscriptionId']) && !$subscription->subscription_id) {
            $subscription->status = 'error';
            $subscription->note = json_encode($response);
            $subscription->save();

            $transaction->batch_id = $response['unitpayId'];
            $transaction->result = 'subscriptionId is missing';
            $transaction->save();
        } else {
            if (empty($transaction->approved)) {
                $transaction->batch_id = $response['unitpayId'];
                $transaction->result = 'Completed';
                $transaction->approved = true;
                $transaction->save();

                $subscription->subscription_id = $response['subscriptionId'];
                $subscription->status = 'active';
                if ($subscription->type == 'PremiumAccount') {
                    if (empty($subscription->date_at)) {
                        $subscription->date_at = now()->addDays(3);
                        $subscription->save();
                    }
                    $subscription->user->updatePremiumStatus(true);
                } else {
                    $subscription->date_at = now()->addDays($subscription->period);
                    $subscription->save();
                    OrdersJapSubscriptionsJob::dispatch($subscription, $transaction)->delay(0);
                }
            }
        }
    }
}
