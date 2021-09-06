<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramUsers;
use App\Models\User;
use App\Modules\Messangers\TelegramModule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class TelegramNotificationJob
 * @package App\Jobs
 */
class TelegramNotificationJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var User $user */
    protected $user;

    /** @var string $code */
    protected $code;

    /** @var array $data */
    protected $data;

    /** @var TelegramBots $bot */
    protected $bot=null;

    /**
     * TelegramNotificationJob constructor.
     * @param User $user
     * @param string $code
     * @param array $data
     * @param TelegramBots $bot
     */
    public function __construct(User $user, string $code, array $data, TelegramBots $bot=null)
    {
        $this->user             = $user;
        $this->code             = $code;
        $this->data             = $data;
        $this->bot              = $bot;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        /** @var TelegramUsers $telegramUsers */
        $telegramUsers = $this->user->telegramUser();

        if (null !== $this->bot) {
            $telegramUsers = $telegramUsers->where('bot_id', $this->bot->id);
        }

        $telegramUsers = $telegramUsers->get();

        /** @var TelegramUsers $telegramUser */
        foreach ($telegramUsers as $telegramUser) {
            if (0 == $telegramUser->chat_id) {
                continue;
            }

            TelegramModule::setLanguageLocale($telegramUser->language);

            /** @var TelegramBots $bot */
            $bot     = $telegramUser->bot()->first();
            $message = view('telegram.notifications.'.$this->code, array_merge([
                'bot'          => $bot,
                'telegramUser' => $telegramUser,
                'user'         => $this,
            ], $this->data))->render();

            if (config('app.env') == 'develop') {
                \Log::info('Prepared VIEW and message for Telegram notification:<hr>'.$message);
            }

            if (empty($message)) {
                continue;
            }

            try {
                $telegramInstance = new TelegramModule($bot->keyword);
                $telegramInstance->sendMessage($telegramUser->chat_id, $message, 'HTML');
            } catch (\Exception $e) {
                continue;
//                throw new \Exception($e->getMessage());
            }
        }
    }
}
