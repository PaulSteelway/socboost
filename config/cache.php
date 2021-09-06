<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file", "memcached", "redis"
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),
    // 'default' => 'memcached',

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env(
        'CACHE_PREFIX',
        str_slug(env('APP_NAME', 'laravel'), '_').'_cache'
    ),

    /*
     * Variable lifetimes
     * All values stored in minutes.
     */

    'lifetimes' => [
        'h' => [ // main helper
            'transactionTypes' => env('H_TRANSACTION_TYPES', 864000), // 10 days
            'currenciesRates' => env('H_CURRENCIES_RATES', 120), // 2 hours
        ],
        'i' => [ // integration helper
            'totalWithdrew' => env('I_TOTAL_WITHDREW', 360), // 6 hours
            'currencies' => env('I_CURRENCIES', 864000), // 10 days
            'runningDays' => env('I_RUNNING_DAYS', 60), // 1 hour
            'totalAccounts' => env('I_TOTAL_ACCOUNTS', 360), // 6 hours
            'activeAccounts' => env('I_ACTIVE_ACCOUNTS', 360), // 6 hours
            'totalDeposited' => env('I_TOTAL_DEPOSITED', 360), // 6 hours
            'tariffPlans' => env('I_TARIFF_PLANS', 86400), // 1 day
            'affiliateLevels' => env('I_AFFILIATE_LEVELS', 86400), // 1 day
            'visitorsOnline' => env('I_VISITORS_ONLINE', 10), // 10 minutes
            'membersOnline' => env('I_MEMBERS_ONLINE', 10), // 10 minutes
            'lastUpdate' => env('I_LAST_UPDATE', 1), // 1 minute
            'partnerArray' => env('I_PARTNER_ARRAY', 30), // 30 minutes
            'userAllDeposits' => env('I_USER_ALL_DEPOSITS', 60), // 60 minutes
            'userActiveDeposits' => env('I_USER_ACTIVE_DEPOSITS', 60), // 60 minutes
            'userClosedDeposits' => env('I_USER_CLOSED_DEPOSITS', 60), // 60 minutes
            'languagesArray' => env('I_LANGUAGES_ARRAY', 86400), // 1 day
            'allNews' => env('I_ALL_NEWS', 180), // 3 hours
            'lastEarnings' => env('I_LAST_EARNINGS', 60), // 1 hour
            'lastWithdraws' => env('I_LAST_WITHDRAWS', 60), // 1 hour
            'lastCreatedDeposits' => env('I_LAST_CREATED_DEPOSITS', 60), // 1 hour
            'lastCreatedMembers' => env('I_LAST_CREATED_MEMBERS', 60), // 1 hour
            'adminEmail' => env('I_ADMIN_EMAIL', 86400), // 1 day
            'dateOfLaunch' => env('I_DATE_OF_LAUNCH', 60), // 1 hour
            'userAllOperations' => env('I_USER_ALL_OPERATIONS', 60), // 1 hour
            'userWallets' => env('I_USER_WALLETS', 60), // 1 hour
            'userIps' => env('I_USER_IPS', 60), // 1 hour
            'userPageViews' => env('I_USER_PAGE_VIEWS', 60), // 1 hour
            'userReferralsTree' => env('I_USER_REFERRALS_TREE', 180), // 3 hours
            'userReferrals' => env('I_USER_REFERRALS', 180), // 3 hours
            'faqsList' => env('I_FAQS_LIST', 864000), // 10 days
            'customerReviews' => env('I_CUSTOMER_REVIEWS', 864000), // 10 days
            'userBalancesByCurrency' => env('I_USER_BALANCES_BY_CURRENCY', 60), // 1 hour
            'userPagesViews' => env('I_USER_PAGES_VIEWS', 60), // 1 hour
            'userTotalDeposited' => env('I_USER_TOTAL_DEPOSITED', 60), // 1 hour
            'userTotalWithdrawn' => env('I_USER_TOTAL_WITHDRAWN', 60), // 1 hour
            'userTotalEarned' => env('I_USER_TOTAL_EARNED', 60), // 1 hour
            'partnerInfoFromCookies' => env('I_PARTNER_INFO_FROM_COOKIES', 60), // 1 hour
            'paymentSystems' => env('I_PAYMENT_SYSTEMS', 864000), // 10 days
            'enterCommission' => env('I_ENTER_COMMISSION', 86400), // 1 day
            'd3v3ReferralsTree' => env('I_D3V3_REFERRALS_TREE', 180), // 3 hours
            'depositsCount' => env('I_DEPOSITS_COUNT', 180), // 3 hours
            'topPartner' => env('I_TOP_PARTNER', 180), // 3 hours
            'telegramBots' => env('I_TELEGRAM_BOTS', 86400), // 1 day
            'getLevels' => env('I_GET_LEVELS', 1440), // 1 day
        ],
        'a' => [ // admin helper
            'd3v3ReferralsTree' => env('A_D3V3_REFERRALS_TREE', 180), // 3 hours
            'withdrawRequestsCount' => env('A_WITHDRAW_REQUESTS_COUNT', 180), // 3 hours
            'transactionsCount' => env('A_TRANSACTIONS_COUNT', 180), // 3 hours
            'depositsSumClosingAtDate' => env('A_DEPOSITS_SUM_CLOSING_AT_DATE', 86400), // 1 day
            'plansPopularity' => env('A_PLANS_POPULARITY', 86400), // 1 day
            'moneyTrafficStatistic' => env('A_MONEY_TRAFFIC_STATISTIC', 86400), // 1 day
            'usersActivityStatistic' => env('A_USERS_ACTIVITY_STATISTIC', 86400), // 1 day
        ],
        'c' => [ // controllers
            'userDeposits' => env('C_USER_DEPOSITS', 60), // 1 hour
            'userOperations' => env('C_USER_OPERATIONS', 60), // 1 hour
        ],
    ],

];
