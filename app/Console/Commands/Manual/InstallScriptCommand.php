<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;

/**
 * Class InstallScriptCommand
 * @package App\Console\Commands\Manual
 */
class InstallScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing script data';

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
        $this->info('Clear cache:');
        $this->call('cache:clear');

        $this->line('===================================');

        $this->info('Generating APP KEY:');
        $this->call('key:generate');

        $this->line('===================================');

        $this->info('Migrations:');
        $this->call('migrate');

        $this->line('===================================');

        $this->info('DB seeds:');
        $this->call('db:seed');

        $this->line('===================================');

        $this->info('Checking script:');
        $this->call('check:script');

        $this->line('===================================');

        $this->info('Registering currencies:');
        $this->call('register:currencies');

        $this->line('===================================');

        $this->info('Registering payment systems:');
        $this->call('register:payment_systems');

        $this->line('===================================');

        $this->info('Registering root user:');
        $this->call('make:root');

        $this->line('===================================');

        $this->info('Publishing languages files:');
        $this->call('publish:language_files');

        $this->line('===================================');

        $askNeedTelegram = $this->ask('Do you need to register Telegram BOT? [yes|no]', 'no');

        if ($askNeedTelegram == 'yes') {
            $this->info('Install telegram bot:');
            $this->call('telegram:register_bot');
        }

        $this->line('===================================');

        $askNeedCheckPS = $this->ask('Do you want to check payment systems? [yes|no]', 'no');

        if ($askNeedCheckPS == 'yes') {
            $this->info('Checking payment systems:');
            $this->call('check:payment_systems_connections');
        }

        $this->line('===================================');
        $needDemoData = $this->ask('Do you need demo data? [yes|no]', false);

        if ($needDemoData == 'yes') {
            $this->call('generate:demo_data');
        }
    }
}
