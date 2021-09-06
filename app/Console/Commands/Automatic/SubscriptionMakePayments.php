<?php

namespace App\Console\Commands\Automatic;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Modules\PaymentSystems\UnitpayModule;
use Illuminate\Console\Command;

class SubscriptionMakePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:make_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make recursive payment by Subscription';

    /**
     * SubscriptionMakePayment constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = Subscription::select('subscriptions.*')
            ->join('transactions', 'transactions.source', '=', 'subscriptions.id')
            ->join('transaction_types', function ($join) {
                $join->on('transaction_types.id', '=', 'transactions.type_id')
                    ->where('transaction_types.name', '=', 'subscription');
            })
            ->join('payment_systems', function ($join) {
                $join->on('payment_systems.id', '=', 'transactions.payment_system_id')
                    ->where('payment_systems.code', '=', 'unitpay');
            })
            ->where('subscriptions.status', 'active')
            ->where('subscriptions.date_at', '<', now()->format('Y-m-d'))
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            try {
                $checking = UnitpayModule::getSubscriptionDetails($subscription);
                if (in_array($checking->result->status, ['active', 'new'])) {
                    if ($subscription->type == 'PremiumAccount') {
                        /** @var Transaction $transaction */
                        $transaction = Transaction::subscriptionPremiumAccount($subscription, 'unitpay', 199);
                    } else {
                        /** @var Transaction $transaction */
                        $transaction = Transaction::subscriptionOrder($subscription);
                    }

                    $result = UnitpayModule::makeSubscriptionTransaction($subscription, $transaction);
                    if (!empty($result->error->message)) {
                        $transaction->result = $result->error->message;
                        $transaction->save();
                        throw new \Exception($result->error->message);
                    }
                    $subscription->date_at = now()->addDays($subscription->period);
                    $subscription->save();
                } else {
                    throw new \Exception('Subscription is no longer active');
                }
            } catch (\Exception $e) {
                if ($subscription->type == 'PremiumAccount') {
                    $subscription->user->updatePremiumStatus(false);
                }
                $subscription->status = 'error';
                $subscription->note = $e->getMessage();
                $subscription->save();
            }
        }
    }
}
