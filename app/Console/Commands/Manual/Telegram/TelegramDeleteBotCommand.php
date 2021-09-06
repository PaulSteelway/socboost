<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual\Telegram;

use App\Models\Telegram\TelegramBots;
use Illuminate\Console\Command;

/**
 * Class TelegramDeleteBotCommand
 * @package App\Console\Commands\Manual\Telegram
 */
class TelegramDeleteBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:delete_bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete telegram bot.';

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
        $this->line('------');
        $this->comment('Deleting Telegram BOT for ' . config('app.name'));
        $this->line('');

        $keyword = $this->ask('Enter Telegram BOT keyword (example: "example_bot").');
        $sure    = $this->ask('Are you sure, that you want to delete '.$keyword.' Telegram BOT with all data (webhooks, users etc...) ?? (enter yes)', 'no');

        if ('yes' !== $sure) {
            $this->warn('Operation stopped ..');
            return;
        }

        $searchBot = TelegramBots::where('keyword', $keyword)->first();

        if (null === $searchBot) {
            $this->error('Bot is not found.');
            return;
        }

        $searchBot->delete();
        $this->info('Bot, webhooks and their info, users, and other data - was removed from DB. External webhooks links was removed.');
    }
}
