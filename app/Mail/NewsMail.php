<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Markdown;
use Illuminate\Queue\SerializesModels;

/**
 * Class NotificationMail
 * @package App\Mail
 */
class NewsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var int $tries */
    public $tries = 1;

    /** @var User $user */
    protected $user;

    /** @var string $code */
    public $subject;

    /** @var string $data */
    public $body;

    /** @var string $lang */
    public $lang;

    /**
     * Notification constructor.
     * @param User $user
     * @param string $code
     * @param array|null $data
     */
    public function __construct(User $user, string $subject, string $body)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
        $this->lang = !empty($user->country) && !in_array($user->country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
    }

    /**
     * @return NewsMail
     * @throws \Throwable
     */
    public function build()
    {
//        sleep(37);
//        $body = $this->body;
//        $markdown = Container::getInstance()->make(Markdown::class);
//        $html = $markdown->render('mail.body.news', compact('body'));
        return $this->from('support@socialbooster.me')
            ->to($this->user->email)
            ->subject($this->subject)
            ->view('mail.body.raw_news')->with('html', $this->body);
    }


}
