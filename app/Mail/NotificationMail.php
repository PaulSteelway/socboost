<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class NotificationMail
 * @package App\Mail
 */
class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var int $tries */
    public $tries = 1;

    /** @var User $user */
    protected $user;

    /** @var string $code */
    protected $code;

    /** @var array $data */
    protected $data;

    /**
     * Notification constructor.
     * @param User $user
     * @param string $code
     * @param array|null $data
     */
    public function __construct(User $user, string $code, array $data=null)
    {
        $this->user     = $user;
        $this->data     = $data;
        $this->code     = $code;
    }

    /**
     * @return NotificationMail|null
     * @throws \Throwable
     */
    public function build()
    {
        $subjectView    = 'mail.subject.'.$this->code;
        $bodyView       = 'mail.body.'.$this->code;

        if (!view()->exists($subjectView) || !view()->exists($bodyView)) {
            \Log::info('View for mail not found - '.$subjectView.' OR '.$bodyView);
            return null;
        }

        $html = view('mail.body.'.$this->code, array_merge([
            'user'      => $this->user,
            'subject'   => $this->subject,
        ], $this->data))->render();

        if (empty($html)) {
            return null;
        }

        return $this->from('support@socialbooster.me')
            ->to($this->user->email)
            ->subject(view($subjectView, array_merge([
                'user'      => $this->user,
                'subject'   => $this->subject,
            ], $this->data))->render())
            ->view('mail.body.'.$this->code, array_merge([
                'user'      => $this->user,
                'subject'   => $this->subject,
            ], $this->data));
    }
}
