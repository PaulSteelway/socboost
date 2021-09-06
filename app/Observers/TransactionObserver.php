<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Transaction;

/**
 * Class TransactionObserver
 * @package App\Observers
 */
class TransactionObserver
{
    /**
     * @param Transaction $transaction
     * @return array
     * @throws
     */
    private function getCacheKeys(Transaction $transaction): array
    {
        if (null == $transaction->user_id) {
            return [];
        }

        $keys = [
            'i.lastUpdate',
            'a.transactionsCount',
        ];

        if ($transaction->type->name == 'withdraw') {
            $keys[] = 'i.totalWithdrew';
            $keys[] = 'a.withdrawRequestsCount';
            $keys[] = 'usersWithdrawals.user-' . $transaction->user_id;
        }

        return $keys;
    }

    /**
     * @param Transaction $transaction
     * @return array
     * @throws \Exception
     */
    private function getCacheTags(Transaction $transaction): array
    {
        if (null == $transaction->user_id) {
            return [];
        }

        $tags = [
            'userBalancesByCurrency.' . $transaction->user_id,
            'userAllOperations.' . $transaction->user_id
        ];

        if ($transaction->type->name == 'enter') {
            $tags[] = 'userTotalDeposited.' . $transaction->user_id;
            $tags[] = 'totalDeposited';
        }

        if ($transaction->type->name == 'withdraw') {
            $tags[] = 'userTotalWithdrawn.' . $transaction->user_id;
            $tags[] = 'lastWithdrawals';
        }

        if ($transaction->type->name == 'dividend') {
            $tags[] = 'userTotalEarned.' . $transaction->user_id;
            $tags[] = 'lastEarnings';
        }

        return $tags;
    }

    /**
     * Listen to the Transaction created event.
     *
     * @param Transaction $transaction
     * @return void
     * @throws
     */
    public function created(Transaction $transaction)
    {
        clearCacheByArray($this->getCacheKeys($transaction));
        clearCacheByTags($this->getCacheTags($transaction));
    }

    /**
     * Listen to the Transaction deleting event.
     *
     * @param Transaction $transaction
     * @return void
     * @throws
     */
    public function deleted(Transaction $transaction)
    {
        clearCacheByArray($this->getCacheKeys($transaction));
        clearCacheByTags($this->getCacheTags($transaction));
    }

    /**
     * Listen to the Transaction updating event.
     *
     * @param Transaction $transaction
     * @return void
     * @throws
     */
    public function updated(Transaction $transaction)
    {
        clearCacheByArray($this->getCacheKeys($transaction));
        clearCacheByTags($this->getCacheTags($transaction));
    }
}