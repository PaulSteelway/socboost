<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVoucher extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string $lang */
    public $lang;

    /** @var Voucher $voucher */
    public $voucher;

    /**
     * EmailVoucher constructor.
     * @param User $user
     * @param Voucher $voucher
     */
    public function __construct(User $user, Voucher $voucher)
    {
        $this->lang = !empty($user->country) && !in_array($user->country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
        $this->voucher = $voucher;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->lang == 'en' ? 'Voucher' : 'Ваучер';
        return $this->subject($subject)
            ->view('mail.email_voucher');
    }
}
