<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Repositories\PacketRepository;

class SoundCloudRepostsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packets = PacketRepository::getPacketsByService('SOUND_CLOUD_REPOSTS');

        return view('customer.order.SoundCloudRepostsController', ['packets' => $packets->toArray()]);
    }
}
