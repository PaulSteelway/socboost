<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\Telegram\TelegramWebhooksInfo;
use Yajra\DataTables\DataTables;

/**
 * Class WebhooksInfoController
 * @package App\Http\Controllers\Admin\Telegram
 */
class WebhooksInfoController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.webhooks_info.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $webhooksInfo = TelegramWebhooksInfo::select('*')->with([
            'webhook'
        ]);

        return DataTables::of($webhooksInfo)
            ->addColumn('bot_username', function($info) {
                /** @var TelegramWebhooks $webhook */
                $webhook = $info->webhook;
                
                if (null === $webhook) {
                    return __('webhook not found');
                }

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
