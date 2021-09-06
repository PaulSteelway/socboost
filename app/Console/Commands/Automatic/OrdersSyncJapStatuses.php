<?php

namespace App\Console\Commands\Automatic;

use App\Jobs\OrdersSyncJapStatusesJob;
use App\Models\Order;
use Illuminate\Console\Command;

class OrdersSyncJapStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync_jap_statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync statuses of JAP orders every 30 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function handle()
    {
        $orders = Order::whereNotNull('packet_id')
            ->whereNotNull('jap_id')
            ->whereNotIn('jap_status', ['Completed', 'Canceled', 'Error'])
            ->whereRaw('created_at > DATE_SUB(now(), INTERVAL 90 DAY)')
            ->get();
        if ($orders->isNotEmpty()) {
            foreach ($orders as $order) {
//                OrdersSyncJapStatusesJob::dispatch($order)->onQueue('orders_sync_statuses')->delay(0);
                OrdersSyncJapStatusesJob::dispatch($order)->delay(0);
            }
        }
    }
}
