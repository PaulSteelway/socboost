<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationPasswordReset
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        //
        $data = [
            'user'=>
                [
                    'name' => $event->user->name,
                    'email'=>$event->user->email,
                    'timestamp'=>now()
                ]
        ];
        $event->user->sendNotification('password_reset',$data);
    }
}
