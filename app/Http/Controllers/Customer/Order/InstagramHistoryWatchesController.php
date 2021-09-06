<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Repositories\PacketRepository;

class InstagramHistoryWatchesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packets = PacketRepository::getPacketsByService('INSTAGRAM_HISTORY_WATCHES');

        return view('customer.order.InstagramHistoryWatchesController', ['packets' => $packets->toArray()]);
    }
}
