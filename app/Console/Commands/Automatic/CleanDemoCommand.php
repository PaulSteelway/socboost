<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use Illuminate\Console\Command;

/**
 * Class CleanDemoCommand
 * @package App\Console\Commands
 */
class CleanDemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all DEMO data';

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
     * Launch: php artisan cache:clear && php artisan config:cache && php artisan migrate:reset && php artisan migrate && php artisan db:seed && php artisan register:currencies true && php artisan register:payment_systems true && php artisan make:root true && php artisan generate:demo_data
     * @return mixed
     */
    public function handle()
    {
        if (config('app.env') != 'demo') {
            $this->info('Project ENV is not DEMO. Skip cleaning.');
            return;
        }

        $this->call('clear-compiled');
        $this->call('cache:clear');

        \Redis::connection('default')->del('queues:'.getSupervisorName().'-low');
        \Redis::connection('default')->del('queues:'.getSupervisorName().'-high');
        \Redis::connection('default')->del('queues:'.getSupervisorName().'-emails');
        \Redis::connection('default')->del('queues:'.getSupervisorName().'-default');

        $this->call('migrate:reset');
        $this->call('migrate');
        $this->call('db:seed');

        $this->call('register:currencies', [
            'demo' => true
        ]);
        $this->call('register:payment_systems', [
            'demo' => true
        ]);
        $this->call('make:root', [
            'demo' => true
        ]);
        $this->call('check:script');
        $this->call('publish:language_files');
        $this->call('generate:demo_data');
    }
}
