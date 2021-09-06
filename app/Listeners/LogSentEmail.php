<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Listeners;

use App\Models\MailSent;
use App\Models\User;
use Illuminate\Mail\Events\MessageSent;

class LogSentEmail
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
     * @param MessageSent $messageSent
     */
    public function handle(MessageSent $messageSent)
    {
        $message    = $messageSent->message;

        $email      = current(array_keys($messageSent->message->getTo()));
        $text       = $message->getBody();
        $subject    = $messageSent->message->getSubject();
        /** @var User $user */
        $user       = User::where('email', $email)->first();

        if (null === $user) {
            \Log::info('User not found for email log');
        }

        $mailSent = new MailSent();
        $mailSent->text = $text;
        $mailSent->email = $email;
        $mailSent->subject = $subject;
        $mailSent->user()->associate($user);
        $mailSent->save();
    }
}
