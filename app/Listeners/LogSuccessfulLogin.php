<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Listeners;

use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
        $event->user->addIp();
        $this->checkBonusForEveryDayLogin($event->user);
    }

    /**
     * @param User $user
     */
    private function checkBonusForEveryDayLogin(User $user)
    {
        if (!empty($user->last_login)) {
            if (Carbon::createFromTimeString($user->last_login)->addDay()->toDateString() === Carbon::today()->toDateString()) {
                $wallet = $user->getActiveWallet();
                /** @var Wallet $wallet */
                $wallet->addBonus(15);

                session(['user.bonus.daily.login' => true]);
            }
        }

        $user->last_login = now();
        $user->save();
    }
}
