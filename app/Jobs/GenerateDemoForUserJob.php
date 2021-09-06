<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs;

use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Rate;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Faker\Factory;

/**
 * Class GenerateDemoForUserJob
 * @package App\Jobs
 */
class GenerateDemoForUserJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var User */
    protected $user;

    /** @var int $stressLevel */
    protected $stressLevel;

    /** @var \Faker\Generator $faker */
    protected $faker;

    /**
     * GenerateDemoForUser constructor.
     * @param User $user
     * @param int $stressLevel
     */
    public function __construct(User $user, int $stressLevel)
    {
        $this->user         = $user;
        $this->stressLevel  = $stressLevel;
        $this->faker        = Factory::create();
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $this->generateBalances();
        $this->generateWithdrawals();
        $this->generateDeposits();
    }

    /**
     * @throws \Throwable
     */
    private function generateBalances()
    {
        $transactionType = TransactionType::getByName('enter');

        /** @var Wallet $wallet */
        foreach ($this->user->wallets()->get() as $wallet) {
            for($i = 1; $i <= $this->faker->numberBetween(3,5); $i++) {
                $amount             = $this->faker->numberBetween(10, 1000);
                $externalWallet     = 'W' . $this->faker->randomNumber(5);
                $transactionData    = [
                    'amount'            => $amount,
                    'type_id'           => $transactionType->id,
                    'user_id'           => $wallet->user_id,
                    'wallet_id'         => $wallet->id,
                    'currency_id'       => $wallet->currency_id,
                    'payment_system_id' => $wallet->payment_system_id,
                    'result'            => 'completed',
                    'batch_id'          => 'B'.$this->faker->randomNumber(5),
                    'approved'          => 1,
                    'log'               => $this->faker->text,
                    'created_at'        => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
                ];
                $wallet->refill($transactionData['amount'], $externalWallet);
                Transaction::create($transactionData);
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function generateWithdrawals()
    {
        $wallets = Wallet::where('user_id', $this->user->id)
            ->where('balance', '>', 10)
            ->inRandomOrder()
            ->limit(1);

        if (0 === $wallets->count()) {
            return;
        }

        /** @var Wallet $wallet */
        foreach ($wallets->get() as $wallet) {
            /** @var Currency $currency */
            $currency   = $wallet->currency()->first();
            $amount     = $this->faker->randomFloat($currency->precision, 0.5, 5);

            $transaction = Transaction::withdraw($wallet, $amount);

            if (null !== $transaction && $this->faker->boolean) {
                $transaction->approved = 1;
                $transaction->save();
            }
        }
    }

    /**
     * @throws \Throwable
     */
    private function generateDeposits()
    {
        /** @var Rate $randomRate */
        $randomRate = Rate::where('active', 1)
            ->inRandomOrder()
            ->limit(1)
            ->first();

        if (null === $randomRate) {
            return;
        }

        $wallet = $this->user->wallets()
            ->where('currency_id', $randomRate->currency_id)
            ->first();

        if (null === $wallet) {
            return;
        }

        $enterTransaction    = TransactionType::getByName('enter');
        $externalWallet      = 'W' . $this->faker->randomNumber(5);
        $transactionData     = [
            'amount'            => $randomRate->max,
            'type_id'           => $enterTransaction->id,
            'user_id'           => $wallet->user_id,
            'wallet_id'         => $wallet->id,
            'currency_id'       => $wallet->currency_id,
            'payment_system_id' => $wallet->payment_system_id,
            'result'            => 'completed',
            'batch_id'          => 'B'.$this->faker->randomNumber(5),
            'approved'          => 1,
            'log'               => $this->faker->text,
            'created_at'        => now(),
        ];
        $wallet->refill($transactionData['amount'], $externalWallet);
        Transaction::create($transactionData);

        $min = $randomRate->min == 0 ? 1 : $randomRate->min;
        $depositAmount = $this->faker->numberBetween($min, $randomRate->max);
        $depositData = [
            'wallet_id'  => $wallet->id,
            'rate_id'    => $randomRate->id,
            'amount'     => $depositAmount,
            'reinvest'   => $randomRate->reinvest ? $this->faker->numberBetween(0, 20) : 0,
            'created_at' => now(),
            'user'       => $wallet->user()->first(),
        ];
        Deposit::addDeposit($depositData);
    }
}