<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Repositories\PacketRepository;

class TikTokLikesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packets = PacketRepository::getPacketsByService('TIKTOK_LIKES');

        return view('customer.order.TikTokLikesController', ['packets' => $packets->toArray()]);
    }
}
