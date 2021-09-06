<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs;

use App\Models\Deposit;
use App\Models\DepositQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class AccrueDeposit
 * @package App\Jobs
 */
class AccrueDeposit implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Deposit $deposit */
    protected $deposit;

    /** @var DepositQueue $depositQueue */
    protected $depositQueue;

    /**
     * AccrueDeposit constructor.
     * @param Deposit $deposit
     * @param DepositQueue $depositQueue
     */
    public function __construct(Deposit $deposit, DepositQueue $depositQueue)
    {
        $this->deposit      = $deposit;
        $this->depositQueue = $depositQueue;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->deposit->accrue($this->depositQueue);
    }
}
