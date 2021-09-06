<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooks;
use Yajra\DataTables\DataTables;

/**
 * Class WebhooksController
 * @package App\Http\Controllers\Admin\Telegram
 */
class WebhooksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.webhooks.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        /** @var TelegramWebhooks $events */
        $webhooks = TelegramWebhooks::select('*')->with([
            'bot'
        ]);

        return DataTables::of($webhooks)
            ->addColumn('bot_username', function ($webhook) {
                /** @var TelegramBots $bot */
                $bot = $webhook->bot;

                if (null === $bot) {
                    return __('bot not found');
                }

                return $bot->username;
            })
            ->make(true);
    }
}
