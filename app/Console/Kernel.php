<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console;

use App\Console\Commands\Automatic\ArchiveBlockioAddressesCommand;
use App\Console\Commands\Automatic\CheckPaymentSystemsConnectionsCommand;
use App\Console\Commands\Automatic\CleanAfterDeploymentCommand;
use App\Console\Commands\Automatic\CleanDemoCommand;
use App\Console\Commands\Automatic\CleanSentMailsCommand;
use App\Console\Commands\Automatic\DepositQueueCommand;
use App\Console\Commands\Automatic\FillCacheCommand;
use App\Console\Commands\Automatic\GenerateDemoDataCommand;
use App\Console\Commands\Automatic\ProcessInstantPaymentsCommand;
use App\Console\Commands\Automatic\PublishLanguageFilesCommand;
use App\Console\Commands\Automatic\ScanSysLoadCommand;
use App\Console\Commands\Automatic\ScriptCheckerCommand;
use App\Console\Commands\Automatic\TaskCheck\CheckAllScopes;
use App\Console\Commands\Automatic\TaskCheck\CleanTasksWithoutActions;
use App\Console\Commands\Automatic\TaskCheck\Facebook\FacebookNewFriendsCommand;
use App\Console\Commands\Automatic\TaskCheck\Facebook\FacebookPageLikeCommand;
use App\Console\Commands\Automatic\TaskCheck\Telegram\TelegramChannelSubscriptionCommand;
use App\Console\Commands\Automatic\TaskCheck\VK\VkPageSubscriptionCommand;
use App\Console\Commands\Automatic\TaskCheck\VK\VkPostLikeCommand;
use App\Console\Commands\Automatic\TaskCheck\Youtube\YoutubeChannelSubscriptionCommand;
use App\Console\Commands\Automatic\TaskCheck\Youtube\YoutubeVideoCommentCommand;
use App\Console\Commands\Automatic\TaskCheck\Youtube\YoutubeVideoLikeCommand;
use App\Console\Commands\Automatic\TaskCheck\Youtube\YoutubeVideoWatchCommand;
use App\Console\Commands\Automatic\Telegram\ClearTelegramBotHistoryCommand;
use App\Console\Commands\Automatic\Telegram\UpdateWebhookInfoCommand;
use App\Console\Commands\Automatic\UpdateCurrencyRatesCommand;
use App\Console\Commands\Manual\CheckUsersBalancesCommand;
use App\Console\Commands\Manual\CreateRootCommand;
use App\Console\Commands\Manual\InstallScriptCommand;
use App\Console\Commands\Manual\RegisterCurrenciesCommand;
use App\Console\Commands\Manual\RegisterPaymentSystemsCommand;
use App\Console\Commands\Manual\Telegram\TelegramDeleteBotCommand;
use App\Console\Commands\Manual\Telegram\TelegramRegisterBotCommand;
use App\Console\Commands\Manual\Telegram\TelegramSetWebhookCommand;
use App\Console\Commands\Manual\UnarchiveAllBlockioAddressesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        /*
         * Each different
         */
        CleanAfterDeploymentCommand::class,
        CleanDemoCommand::class,
        GenerateDemoDataCommand::class,
        CreateRootCommand::class,
        InstallScriptCommand::class,
        RegisterCurrenciesCommand::class,
        RegisterPaymentSystemsCommand::class,
        ProcessInstantPaymentsCommand::class,
        PublishLanguageFilesCommand::class,
        CheckPaymentSystemsConnectionsCommand::class,
        ArchiveBlockioAddressesCommand::class,
        UnarchiveAllBlockioAddressesCommand::class,
        ScriptCheckerCommand::class,
        TelegramRegisterBotCommand::class,
        TelegramDeleteBotCommand::class,
        TelegramSetWebhookCommand::class,
        ClearTelegramBotHistoryCommand::class,
        UpdateWebhookInfoCommand::class,
        ScanSysLoadCommand::class,
        DepositQueueCommand::class,
        CheckUsersBalancesCommand::class,
        CleanSentMailsCommand::class,
        FillCacheCommand::class,

        /*
         * Task scopes
         */
        FacebookNewFriendsCommand::class,
        FacebookPageLikeCommand::class,

        TelegramChannelSubscriptionCommand::class,

        VkPageSubscriptionCommand::class,
        VkPostLikeCommand::class,

        YoutubeChannelSubscriptionCommand::class,
        YoutubeVideoCommentCommand::class,
        YoutubeVideoLikeCommand::class,
        YoutubeVideoWatchCommand::class,

        CheckAllScopes::class,
        CleanTasksWithoutActions::class,
        UpdateCurrencyRatesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Logs
        // Потерялся чистильщик
        // $schedule->command('log:clear')->daily()->withoutOverlapping();

        // Old information
        $schedule->command('telegram:clear_history')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('clean:sent_mails')->everyTenMinutes()->withoutOverlapping();

        // Demo
        $schedule->command('clean:demo')->daily()->at('00:01');

        // Jobs
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // Financial
        $schedule->command('check:payment_systems_connections')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('process:instant_payments')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('deposits:queue')->everyTenMinutes()->withoutOverlapping();

        // Languages
        $schedule->command('publish:language_files')->everyTenMinutes()->withoutOverlapping();

        // Backups
        $schedule->command('backup:clean')->twiceDaily();
        $schedule->command('backup:run', ['--only-db'])->twiceDaily();

        // Licence && server
        $schedule->command('check:script')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('scan:sys_load')->everyMinute()->withoutOverlapping();

        // User tasks
        $schedule->command('task_check:all')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('task_check:clean_without_actions')->hourly()->withoutOverlapping();
        $schedule->command('task_check:clean_dead_task_propositions')->daily()->withoutOverlapping();

        // Update webhook info
        $schedule->command('telegram:update_webhook_info')->everyMinute()->withoutOverlapping();

        // CACHE
        $schedule->command('fill:cache')->hourly()->withoutOverlapping();

        // External workers
        $schedule->command('update:currency_rates')->hourly()->withoutOverlapping();

        // OrdersSyncJapStatuses
        $schedule->command('orders:sync_jap_statuses')->hourly();

        // SubscriptionMakePayments
        $schedule->command('subscription:make_payments')->daily();

        // SubscriptionUpdateConditions
        $schedule->command('subscription:update_conditions')->daily();

        // SitemapGenerate
        $schedule->command('sitemap:generate')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands/Automatic');
        $this->load(__DIR__ . '/Commands/Manual');

        require base_path('routes/console.php');
    }
}
