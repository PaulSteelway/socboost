<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAutoRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User $user */
    public $user;
    public $lang;
    public $password;
    public $settingsUrl;

    /**
     * EmailAutoRegistration constructor.
     * @param User $user
     * @param string $password
     * @param string $token
     */
    public function __construct(User $user, $password, $token)
    {
        $this->user = $user;
        $this->lang = !empty($user->country) && !in_array($user->country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
        $this->password = $password;
        $this->settingsUrl = url(config('app.url') . route('password.reset', [$token, 'email' => $user->email], false));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->lang == 'en' ? 'Account SocialBooster' : 'Аккаунт SocialBooster';
        return $this->subject($subject)
            ->view('mail.auth.auto_registration');
    }
}
