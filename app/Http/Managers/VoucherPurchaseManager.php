<?php

namespace App\Http\Managers;

use App\Mail\EmailVoucher;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Mail;

class VoucherPurchaseManager
{
    /** @var User $user */
    private $user;

    /** @var array $suggestion */
    private $suggestion;

    /** @var Wallet $wallet */
    private $wallet;

    /** @var float $summary */
    private $summary;

    /** @var Voucher $voucher */
    private $voucher;

    /**
     * VoucherPurchaseManager constructor.
     * @param User $user
     * @param array $suggestion
     * @throws Exception
     */
    public function __construct(User $user, array $suggestion)
    {
        $this->user = $user;
        $this->suggestion = $suggestion;
    }

    /**
     * @throws Exception
     */
    public function processing()
    {
        $this->getWallet();
        $this->getSummary();
        $this->createVoucher();
        $this->sendVoucherViaEmail();
        $this->createOrder();
        $this->removeAmount();
    }

    /**
     * @throws Exception
     */
    private function getWallet()
    {
        $this->wallet = $this->user->getActiveWallet();
        if (empty($this->wallet)) {
            throw new Exception(__('User wallet not found'));
        }
    }

    /**
     * @throws Exception
     */
    private function getSummary()
    {
        $this->summary = $this->suggestion['offer'];
        if ($this->wallet->balance < $this->summary) {
            throw new Exception(__('You do not have enough funds in your wallet.'));
        }
    }

    private function createVoucher()
    {
        /** @var Currency $currency */
        $currency = Currency::where('code', 'RUR')->first();
        $this->voucher = Voucher::create([
            'code' => Voucher::createVoucherCode(),
            'amount' => $this->suggestion['price'],
            'currency_id' => $currency->id,
        ]);
    }

    private function sendVoucherViaEmail()
    {
        Mail::to($this->user)->send(new EmailVoucher($this->user, $this->voucher));
    }

    private function createOrder()
    {
        Order::create([
            'user_id' => $this->user->id,
            'name' => (app()->getLocale() == 'en' ? 'Voucher' : 'Ваучер') . ' № ' . $this->suggestion['id'],
            'quantity' => $this->suggestion['price'],
            'price' => $this->summary,
            'jap_status' => 'Completed'
        ]);
    }

    private function removeAmount()
    {
        $this->wallet->removeAmount($this->summary);
    }
}