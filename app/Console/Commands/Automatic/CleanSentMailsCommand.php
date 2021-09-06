<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Models\MailSent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class CleanSentMailsCommand
 * @package App\Console\Commands\Automatic
 */
class CleanSentMailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:sent_mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old sent emails';

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
        $deleteFrom = Carbon::now()->subHours(96)->toDateTimeString();

        MailSent::where('created_at', '<', $deleteFrom)->delete();
    }
}
