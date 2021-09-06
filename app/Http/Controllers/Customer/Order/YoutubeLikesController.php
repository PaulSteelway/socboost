<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Repositories\PacketRepository;

class YoutubeLikesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packets = PacketRepository::getPacketsByService('YOUTUBE_LIKES');

        return view('customer.order.YoutubeLikesController', ['packets' => $packets->toArray()]);
    }
}
