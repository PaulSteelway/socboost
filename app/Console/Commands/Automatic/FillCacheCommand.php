<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use Illuminate\Console\Command;

/**
 * Class FillCacheCommand
 * @package App\Console\Commands\Automatic
 */
class FillCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically fill all available cache boxes.';

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
        set_time_limit(60*5);

        $this->fillAdminCache();
        $this->fillUserPublicCache();

        $this->info('caching finished');
    }

    /**
     * @throws \Exception
     */
    private function fillAdminCache()
    {
        getAdminWithdrawRequestsCount();
        getAdminTransactionsCount();
        getAdminMergeDepositedAndWithdrew();
        getAdminPlanPopularity();

        foreach(getCurrencies() as $currency) {
            getAdminMoneyTrafficStatistic(30, $currency['code']);
        }

        getAdminUsersActivityStatistic(30);

        $this->info('success cached admin cache');
    }

    /**
     * @throws \Exception
     */
    private function fillUserPublicCache()
    {
        getRunningDays();
        getTotalAccounts();
        getActiveAccounts();

        getTotalDeposited();
        getTotalDeposited(true);

        getTotalWithdrew();
        getTotalWithdrew(true);

        getCurrencies();
        getTariffPlans();
        getAffiliateLevels();
        getVisitorsOnline();
        getMembersOnline();
        getLastUpdate();
        getAllNews();
        getLastEarnings();
        getLastWithdraws();
        getLastCreatedDeposits();
        getLastCreatedMembers();
        getSupportEmail();
        getAdminEmail();
        getLanguagesArray();
        getDateOfLaunch();
        getFaqsList();
        getCustomerReviews();
        getPaymentSystems();
        getEnterCommission();
        getDepositsCount();
        getActiveDepositsCount();
        getClosedDepositsCount();
        getTopPartner();
        getTelegramBots();

        $this->info('success cached user public cache');
    }
}
