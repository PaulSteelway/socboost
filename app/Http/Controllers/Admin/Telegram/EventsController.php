<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use Yajra\Datatables\Datatables;

/**
 * Class EventsController
 * @package App\Http\Controllers\Admin\Telegram
 */
class EventsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.events.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        /** @var TelegramBotEvents $events */
        $events = TelegramBotEvents::select('*');

        return Datatables::of($events)
            ->make(true);
    }
}
