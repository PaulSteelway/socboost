<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Repositories\PacketRepository;

class FacebookLikesPhotosAndPostController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packets = PacketRepository::getPacketsByService('FACEBOOK_LIKE_PHOTO_AND_POST');

        return view('customer.order.FacebookLikesPhotosAndPostController', ['packets' => $packets->toArray()]);
    }
}
