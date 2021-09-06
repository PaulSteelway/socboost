<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic\Telegram;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Modules\Messangers\TelegramModule;
use Illuminate\Console\Command;

/**
 * Class UpdateWebhookInfoCommand
 * @package App\Console\Commands\Automatic\Telegram
 */
class UpdateWebhookInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:update_webhook_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update webhook info.';

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
        /** @var TelegramBots $bot */
        foreach (TelegramBots::get() as $bot) {
            /** @var TelegramWebhooks $webhook */
            foreach ($bot->webhooks()->get() as $webhook) {
                /** @var TelegramWebhooksInfo $webhookInfo */
                foreach ($webhook->webhook_info()->get() as $webhookInfo) {
                    
                    try {
                        $botInstance = new TelegramModule($bot->keyword);
                        $this->info('Successfully got instance of new Telegram BOT.');
                    } catch (\Exception $e) {
                        $this->error('Error while creating instance of Telegram BOT. ' . $e->getMessage());
                        return;
                    }

                    try {
                        $freshWebhook = $botInstance->getWebhookInfo();
                        $freshWebhook = $freshWebhook->result;
                        $this->info('Handled request to Telegram API and got webhook fresh info.');
                    } catch (\Exception $e) {
                        $this->error('Can not get new webhook fresh info. ' . $e->getMessage());
                        return;
                    }

                    $webhookInfo->url                    = $freshWebhook->url ?? null;
                    $webhookInfo->has_custom_certificate = $freshWebhook->has_custom_certificate ?? null;
                    $webhookInfo->pending_update_count   = $freshWebhook->pending_update_count ?? null;
                    $webhookInfo->last_error_date        = $freshWebhook->last_error_date ?? null;
                    $webhookInfo->last_error_message     = $freshWebhook->last_error_message ?? null;
                    $webhookInfo->max_connections        = $freshWebhook->max_connections ?? null;
                    $webhookInfo->allowed_updates        = $freshWebhook->allowed_updates ?? null;
                    $webhookInfo->save();

                    if ($webhookInfo->pending_update_count >= ($webhookInfo->max_connections*2)) {
                        \Log::info('BOT '.$bot->keyword.' has been disabled due over-load activity.');
                        $bot->disabled = 1;
                    } else {
                        $bot->disabled = 0;
                    }

                    $bot->save();

                    try {
                        $getMe = $botInstance->getMe();
                        $getMe = $getMe->result;
                        $this->info('Object getMe was accepted. ' . print_r($getMe, true));
                    } catch (\Exception $e) {
                        $this->error('Can not get object getMe. ' . $e->getMessage());
                        return;
                    }

                    $bot->update([
                        'bot_id'        => $getMe->id,
                        'is_bot'        => $getMe->is_bot,
                        'first_name'    => $getMe->first_name ?? null,
                        'last_name'     => $getMe->last_name ?? null,
                        'username'      => $getMe->username,
                        'language_code' => $getMe->language_code ?? null,
                    ]);

                    $this->line('-------');
                    $this->info('Webhook info updated successfully.');
                }
            }
        }
    }
}
