<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Http\Controllers\Admin\WithdrawalRequestsController;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;
use Illuminate\Console\Command;

/**
 * Class ProcessInstantPaymentsCommand
 * @package App\Console\Commands\Automatic
 */
class ProcessInstantPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:instant_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process customers instant payments.';

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
     * @throws \Exception
     */
    public function handle()
    {
        /** @var TransactionType $transactionWithdrawType */
        $transactionWithdrawType    = TransactionType::getByName('withdraw');
        /** @var Transaction $orders */
        $orders                     = Transaction::where('type_id', $transactionWithdrawType->id)
            ->where('approved', 0)
            ->get();
        $messages                   = [];

        $this->info('Found '.$orders->count().' orders.');
        $this->line('---');

        /** @var Transaction $order */
        foreach ($orders as $order) {
            $this->info('Start processing withdraw order with ID '.$order->id.' and amount '.$order->amount);

            /** @var Wallet $wallet */
            $wallet        = $order->wallet()->first();
            /** @var PaymentSystem $paymentSystem */
            $paymentSystem = $wallet->paymentSystem()->first();
            /** @var Currency $currency */
            $currency      = $wallet->currency()->first();

            if (null == $wallet || null == $paymentSystem) {
                continue;
            }

            if (null === $limits = $paymentSystem->instant_limit) {
                $this->info('Limits is not set up..');
                return;
            }

            $decodedLimits = @json_decode($limits, true);

            if (!isset($decodedLimits[$currency->code])) {
                $this->info('Limit for this currency '.$currency->code.' not found.');
                return;
            }

            $limit = (float) $decodedLimits[$currency->code];

            if ($limit <= 0) {
                $this->info('Skip. Payment system instant limit is 0.');
                continue;
            }

            if ($order->amount > $limit) {
                $this->info('Skip. Order amount '.$order->amount.' and payment system limit '.$limit);
                continue;
            }

            try {
                $message = WithdrawalRequestsController::approve($order->id, true);
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

            $messages[] = $message;
        }

        if (count($messages) == 0) {
            return;
        }

        $msg = 'Processed '.count($messages).' instant payments. Results:\n'.implode('<hr>', $messages);
        $this->info($msg);
        \Log::info($msg);
    }
}
