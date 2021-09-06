<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

/**
 * @param $userId |null
 * @return string|null
 * @throws Exception
 */
function getAdminD3V3ReferralsTree($userId = null)
{
    if (null === $userId) {
        return null;
    }

    return cache()->remember('a.' . $userId . '.d3v3ReferralsTree', getCacheALifetime('d3v3ReferralsTree'), function () use ($userId) {
        return json_encode(\App\Models\User::getD3V3ReferralsTree(\App\Models\User::find($userId)));
    });
}

/**
 * @return int
 * @throws Exception
 */
function getAdminWithdrawRequestsCount(): int
{
    return cache()->remember('a.withdrawRequestsCount', getCacheALifetime('withdrawRequestsCount'), function () {
        /** @var \App\Models\TransactionType $transactionWithdrawalType */
        $transactionWithdrawalType = \App\Models\TransactionType::getByName('withdraw');

        return \App\Models\Transaction::where('type_id', $transactionWithdrawalType->id)
            ->where('approved', 0)
            ->count();
    });
}

/**
 * @param string|null $typeId
 * @return int
 * @throws Exception
 */
function getAdminTransactionsCount($typeId = null): int
{
    return cache()->remember('a.transactionsCount', getCacheALifetime('transactionsCount'), function () use ($typeId) {
        if (null !== $typeId) {
            return \App\Models\Transaction::where('type_id', $typeId)->count();
        }
        return \App\Models\Transaction::count();
    });
}

/**
 * @return array
 *
 * :)
 */
function getAdminMergeDepositedAndWithdrew(): array
{
    return [
        'deposited' => getTotalDeposited(),
        'withdrew' => getTotalWithdrew(),
    ];
}

/**
 * @param \Carbon\Carbon $date
 * @param int $limit
 * @return array
 * @throws Exception
 */
function getAdminDepositsSumClosingAtDate($date = null, $limit = 100): array
{
    if (null == $date || (false == $date instanceof \Carbon\Carbon)) {
        return [];
    }

    return cache()->tags('depositsSumClosingAtDate')->remember('a.depositsSumClosingAtDate.' . $date . '.limit-' . $limit, getCacheALifetime('depositsSumClosingAtDate'), function () use ($date, $limit) {
        $depositsAtDate         = \App\Models\Deposit::where('datetime_closing', 'like', \Carbon\Carbon::parse($date)->toDateString().'%');
        $closingDeposits        = [];
        $closingDepositsSum     = [];

        foreach ($depositsAtDate as $deposit) {
            if (!isset($closingDepositsSum[$deposit->currency->code])) {
                $closingDepositsSum[$deposit->currency->code] = 0;
            }

            $closingDeposits[] = $deposit;
            $closingDepositsSum[$deposit->currency->code] += $deposit->invested;
        }
        return [
            'deposits' => $closingDeposits,
            'total' => $closingDepositsSum,
        ];
    });
}

/**
 * @return array
 * @throws Exception
 */
function getAdminPlanPopularity(): array
{
    return cache()->remember('a.plansPopularity', getCacheALifetime('plansPopularity'), function () {
        $popularity = [];
        $plans = getTariffPlans();

        foreach ($plans as $plan) {
            $depositsSum = \App\Models\Deposit::where('rate_id', $plan['id'])->count();
            $popularity[$plan['id']] = $plan;
            $popularity[$plan['id']]['depositsSum'] = $depositsSum;
        }

        return $popularity;
    });
}

/**
 * @param int $days
 * @param string $currency
 * @return array
 * @throws Exception
 */
function getAdminMoneyTrafficStatistic($days = null, $currency = null)
{
    if (null === $days || null === $currency) {
        return [];
    }

    return cache()->tags('moneyTrafficStatistic')->remember('a.moneyTrafficStatistic.days-' . $days . '.currency-' . $currency, getCacheALifetime('moneyTrafficStatistic'), function () use ($days, $currency) {
        $daysArray = [];
        $currency = \App\Models\Currency::where('code', $currency)->first();
        $typeEnter = \App\Models\TransactionType::getByName('enter');
        $typeWithdraw = \App\Models\TransactionType::getByName('withdraw');

        if (null === $currency || null === $typeEnter || null === $typeWithdraw) {
            return null;
        }

        for ($i = $days; $i > 0; $i--) {
            $day = \Carbon\Carbon::now()->subDays($i);
            $daysArray[$day->format('Y-m-d')] = cache()->tags('moneyTrafficStatistic.specificDay')->rememberForever('a.moneyTrafficStatistic.days-' . $days . '.currency-' . $currency . '.date-' . $day->toFormattedDateString(), function () use ($days, $currency, $typeEnter, $typeWithdraw, $day) {
                $enter = \App\Models\Transaction::where('currency_id', $currency->id)
                    ->where('type_id', $typeEnter->id)
                    ->where('created_at', '>=', $day->format('Y-m-d') . ' 00:00:01')
                    ->where('created_at', '<=', $day->format('Y-m-d') . ' 23:59:59')
                    ->sum('amount');
                $withdrew = \App\Models\Transaction::where('currency_id', $currency->id)
                    ->where('type_id', $typeWithdraw->id)
                    ->where('created_at', '>=', $day->format('Y-m-d') . ' 00:00:01')
                    ->where('created_at', '<=', $day->format('Y-m-d') . ' 23:59:59')
                    ->sum('amount');
                return [
                    'enter' => round($enter, $currency->precision),
                    'withdrew' => round($withdrew, $currency->precision),
                ];
            });
        }
        return $daysArray;
    });
}

/**
 * @param null $days
 * @return array
 * @throws Exception
 */
function getAdminUsersActivityStatistic($days = null)
{
    if (null === $days) {
        return [];
    }

    return cache()->tags('usersActivityStatistic')->remember('a.usersActivityStatistic.days-' . $days, getCacheALifetime('usersActivityStatistic'), function () use ($days) {
        $daysArray = [];

        for ($i = $days; $i > 0; $i--) {
            $day = \Carbon\Carbon::now()->subDays($i);
            $daysArray[$day->format('Y-m-d')] = cache()->tags('usersActivityStatistic.specificDay')->remember('a.usersActivityStatistic.days-' . $days . '.date-' . $day->toDateString(), getCacheALifetime('usersActivityStatistic'), function () use ($days, $day) {
                return [
                    'visitors' => \App\Models\PageViews::select('user_ip')
                        ->distinct()
                        ->where('created_at', '>=', $day->format('Y-m-d') . ' 00:00:01')
                        ->where('created_at', '<=', $day->format('Y-m-d') . ' 23:59:59')
                        ->count(['user_ip']),
                    'pageViews' => \App\Models\PageViews::where('created_at', '>=', $day->format('Y-m-d') . ' 00:00:01')
                        ->where('created_at', '<=', $day->format('Y-m-d') . ' 23:59:59')
                        ->count(),
                ];
            });
        }
        return $daysArray;
    });
}