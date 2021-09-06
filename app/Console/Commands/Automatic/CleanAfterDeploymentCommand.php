<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Class CleanAfterDeploymentCommand
 * @package App\Console\Commands\Automatic
 */
class CleanAfterDeploymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:after_deployment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean data after deployment';

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
     * @throws \Throwable
     */
    public function handle()
    {
        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);

        User::notifyAdminsViaNotificationBot('notification_bot.project_updated', []);
    }
}
