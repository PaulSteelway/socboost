<?php

namespace App\Jobs;

use App\Http\Managers\PaymentManager;
use App\Models\Order;
use App\Modules\FollowizModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrdersSyncJapStatusesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Order $order */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            $order = $this->order->refresh();
            if (!in_array($order->jap_status, ['Completed', 'Canceled', 'Error'])){
                if ($order->packet->service == 'Followiz') {
                    $response = FollowizModule::checkOrderStatus($order);
                } else {
                    $response = JustanotherpanelModule::checkOrderStatus($order);
                }
                if (isset($response->status) && strcasecmp($response->status, 'Canceled') == 0) {
                    PaymentManager::refundByCanceledOrder($order);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
