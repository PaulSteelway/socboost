<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $lang;
    public $verificationUrl;

    /**
     * EmailVerification constructor.
     * @param User $user
     * @param $verificationHash
     */
    public function __construct(User $user, $verificationHash)
    {
        $this->lang = !empty($user->country) && !in_array($user->country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
        $this->verificationUrl = route('email.confirm', ['hash' => $verificationHash]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->lang == 'en' ? 'Verify Email Address' : 'Подтверждение адреса электронной почты';
        return $this->subject($subject)
            ->view('mail.auth.email_verification');
    }
}
