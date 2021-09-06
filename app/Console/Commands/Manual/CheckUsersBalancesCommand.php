<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual;

use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * Class CheckUsersBalancesCommand
 * @package App\Console\Commands\Manual
 */
class CheckUsersBalancesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user_balances {userId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all users balances.';

    /** @var string $userId */
    protected $userId;

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
     * @return mixed
     */
    public function handle()
    {
        $this->userId = (int) $this->argument('userId');

        $positiveIds = $this->getPositiveTransactionIds();
        $negativeIds = $this->getNegativeTransactionIds();

        $user = User::find($this->userId);

        if (null === $user) {
            $this->warn('User not found');
            return;
        }

        $this->info('checking user ' . $user->email . ' (' . $user->login . ')');

        $wallets = $user->wallets;

        foreach ($wallets as $wallet) {
            $balance = $wallet->balance;
            $positiveSum = $wallet->transactions()->whereIn('type_id', $positiveIds)->sum('amount');
            $negativeSum = $wallet->transactions()->whereIn('type_id', $negativeIds)->sum('amount');
            $calculatedBalance = $positiveSum - $negativeSum;

            if ($calculatedBalance < $balance || $calculatedBalance > $balance) {
                $wallet->balance = $calculatedBalance;
                $wallet->save();

                $this->warn('user balance was corrected for wallet ' . $wallet->id . ', from ' . $balance . ' to ' . $calculatedBalance);
            }
        }
    }

    /**
     * @return array
     */
    public function getPositiveTransactionIds(): array
    {
        $positiveTransactionsCodes = [
            'enter',
            'bonus',
            'partner',
            'dividend',
            'close_dep',
        ];
        $positiveTransactionsTypes = TransactionType::select('id')
            ->whereIn('name', $positiveTransactionsCodes)
            ->get()
            ->toArray();

        $ids = [];

        foreach ($positiveTransactionsTypes as $type) {
            $ids[] = $type['id'];
        }

        return $ids;
    }

    /**
     * @return array
     */
    public function getNegativeTransactionIds(): array
    {
        $negativeTransactionsCodes = [
            'withdraw',
            'create_dep',
            'penalty',
        ];
        $negativeTransactionsTypes = TransactionType::select('id')
            ->whereIn('name', $negativeTransactionsCodes)
            ->get()
            ->toArray();

        $ids = [];

        foreach ($negativeTransactionsTypes as $type) {
            $ids[] = $type['id'];
        }

        return $ids;
    }
}
