<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\ProductItem;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAccountInfo extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string $lang */
    public $lang;

    /** @var ProductItem $productItem */
    public $productItem;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, ProductItem $productItem)
    {
        $this->lang = !empty($user->country) && !in_array($user->country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
        $this->productItem = $productItem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->lang == 'en' ? 'Account login info' : 'Информация аккаунта';
        return $this->subject($subject)
            ->view('mail.email_account_info');
    }
}
