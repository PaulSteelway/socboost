<?php

namespace App\Console\Commands\Automatic;

use App\Models\Subscription;
use App\Modules\PaymentSystems\CloudPaymentsModule;
use Illuminate\Console\Command;

class SubscriptionUpdateConditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:update_conditions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update recursive payment conditions by Subscription';

    /**
     * SubscriptionUpdateConditions constructor.
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
                    ->where('payment_systems.code', '=', 'cloudpayments');
            })
            ->where('subscriptions.status', 'Active')
            ->where('subscriptions.date_at', date('Y-m-d', strtotime('tomorrow')))
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            try {
                $result = CloudPaymentsModule::getSubscriptionDetails($subscription);
                if ($result->Success) {
                    $subscription->status = $result->Model->Status;
                    $subscription->save();
                    if ($result->Model->Status == 'Active') {
                        CloudPaymentsModule::updateSubscription($subscription);
                    }
                } else {
                    throw new \Exception(json_encode($result));
                }
            } catch (\Exception $e) {
                $subscription->status = 'Error';
                $subscription->note = $e->getMessage();
                $subscription->save();
            }
        }
    }
}
