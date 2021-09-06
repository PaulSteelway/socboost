<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic\Telegram;

use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class ClearTelegramBotHistoryCommand
 * @package App\Console\Commands\Automatic\Telegram
 */
class ClearTelegramBotHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:clear_history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old messages and logs.';

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
        $deleteFrom = Carbon::now()->subHours(3)->toDateTimeString();

        TelegramBotEvents::where('created_at', '<', $deleteFrom)->delete();
        TelegramBotMessages::where('created_at', '<', $deleteFrom)->delete();
    }
}
