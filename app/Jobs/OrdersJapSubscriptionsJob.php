<?php

namespace App\Jobs;

use App\Http\Managers\OrderManager;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrdersJapSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Subscription $subscription */
    protected $subscription;

    /** @var Transaction $transaction */
    protected $transaction;

    /**
     * OrdersJapSubscriptionsJob constructor.
     *
     * @param Subscription $subscription
     * @param Transaction $transaction
     *
     * return void
     */
    public function __construct(Subscription $subscription, Transaction $transaction)
    {
        $this->subscription = $subscription;
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function handle()
    {
        try {
            OrderManager::createSubscriptionUnitpayOrder($this->subscription, $this->transaction);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
