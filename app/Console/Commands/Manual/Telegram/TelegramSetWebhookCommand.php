<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual\Telegram;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Modules\Messangers\TelegramModule;
use Illuminate\Console\Command;

/**
 * Class TelegramSetWebhookCommand
 * @package App\Console\Commands\Manual\Telegram
 */
class TelegramSetWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set_webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram webhook.';

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
        $this->comment('Setting Telegram webhook');
        $this->line('');

        $keyword = $this->ask('Enter BOT keyword for getting special "Hyipium" functionality (example: "admin_bot", "account_bot", "notification_bot").');

        if (false == in_array($keyword, TelegramBots::getExistsKeywords())) {
            $this->error('This keyword is not exists. Please, try again.');
            return;
        }

        $searchBot = TelegramBots::where('keyword', $keyword)->first();

        if (null === $searchBot) {
            $this->error('Bot is not found.');
            return;
        }

        try {
            $botInstance = new TelegramModule($keyword);
            $this->info('Created instance.');
        } catch (\Exception $e) {
            $this->error('Error while creating instance of Telegram BOT. '.$e->getMessage());
            return;
        }

        $max_connections = $this->ask('Enter maximum connections (default 40)', 40);

        try {
            $webhook = $botInstance->setWebhook($searchBot, null, $max_connections);
            $this->info('Webhook was installed.');
        } catch (\Exception $e) {
            $this->error('Webhook can not be installed: '.$e->getMessage());
            return;
        }

        try {
            $newWebhookInfo = $botInstance->getWebhookInfo();
            $newWebhookInfo = $newWebhookInfo->result;
            $this->info('Handled request to Telegram API and got new webhook fresh info.');
        } catch (\Exception $e) {
            $this->error('Can not get new webhook fresh info. '.$e->getMessage());
            return;
        }

        $webhookInfoData = [
            'telegram_webhook_id'    => $webhook->id,
            'url'                    => $newWebhookInfo->url ?? null,
            'has_custom_certificate' => $newWebhookInfo->has_custom_certificate ?? null,
            'pending_update_count'   => $newWebhookInfo->pending_update_count ?? null,
            'last_error_date'        => $newWebhookInfo->last_error_date ?? null,
            'last_error_message'     => $newWebhookInfo->last_error_message ?? null,
            'max_connections'        => $newWebhookInfo->max_connections ?? null,
            'allowed_updates'        => $newWebhookInfo->allowed_updates ?? null,
        ];

        try {
            TelegramWebhooksInfo::create($webhookInfoData);
            $this->info('New webhook info was registered in DB. '.print_r($webhookInfoData,true));
        } catch (\Exception $e) {
            $this->error('Can not register new webhook info in DB. '.$e->getMessage());
            return;
        }

        $this->line('-------');
        $this->info('Bot was installed successfully.');

        return;
    }
}
