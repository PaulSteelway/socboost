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
 * Class TelegramRegisterBotCommand
 * @package App\Console\Commands\Manual\Telegram
 */
class TelegramRegisterBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:register_bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register telegram bot and create new webhook.';

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
        $this->comment('Registering Telegram BOT for '.config('app.name'));
        $this->line('');

        $token   = $this->ask('Enter BOT token (example: 582034713:AAFN_zZ5UHvazOjCq0rnV6DXcesm5ZlL7iM).');
        $keyword = $this->ask('Enter BOT keyword for getting special "Hyipium" functionality (example: "admin_bot", "account_bot", "notification_bot").');

        $checkToken = TelegramBots::where('token', $token)->count();

        if ($checkToken > 0) {
            $this->error('Such bot already registered with token '.$token);
            return;
        }

        $this->info('BOT with same token not found, continue installing.');

        if (false == in_array($keyword, TelegramBots::getExistsKeywords())) {
            $this->error('This keyword is not exists. Please, try again.');
            return;
        }

        $this->info('Keyword is exists, continue installing.');

        try {
            $bot = TelegramBots::create([
                'token' => $token,
                'keyword' => $keyword,
            ]);
            $this->info('Bot registered in DB.');
        } catch (\Exception $e) {
            $this->error('Error while registering new Telegram BOT. '.$e->getMessage());
            return;
        }

        try {
            $botInstance = new TelegramModule($bot->keyword);
            $this->info('Successfully got instance of new Telegram BOT.');
        } catch (\Exception $e) {
            $this->error('Error while creating instance of Telegram BOT. '.$e->getMessage());
            return;
        }

        $certificate     = $this->ask('Enter self-signed certificate file address, if exists.', 'no');
        $max_connections = $this->ask('Enter maximum connections (default 40)', 40);

        if ('no' !== $certificate) {
            if (\File::exists($certificate)) {
                $certificate = \File::get($certificate);
                $this->info('Certificate found and added to request.');
            } else {
                $this->error('Certificate file can not be found. Stopping.');
                foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                    $bot->delete();
                }
                return;
            }
        } else {
            $certificate = null;
            $this->info('Certificate field is empty, skipping this parameter.');
        }

        try {
            $webhook = $botInstance->setWebhook($bot, $certificate, $max_connections);
            $this->info('Webhook was installed.');
        } catch (\Exception $e) {
            $this->error('Webhook can not be installed: '.$e->getMessage());
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return;
        }

        try {
            $newWebhookInfo = $botInstance->getWebhookInfo();
            $newWebhookInfo = $newWebhookInfo->result;
            $this->info('Handled request to Telegram API and got new webhook fresh info.');
        } catch (\Exception $e) {
            $this->error('Can not get new webhook fresh info. '.$e->getMessage());
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
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
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return;
        }

        try {
            $getMe = $botInstance->getMe();
            $getMe = $getMe->result;
            $this->info('Object getMe was accepted. '.print_r($getMe,true));
        } catch (\Exception $e) {
            $this->error('Can not get object getMe. '.$e->getMessage());
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
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
        $this->info('Bot was installed successfully.');

        return;
    }
}
