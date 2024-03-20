<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @package App\Models
 *
 * @property string id
 * @property string user_id
 * @property string currency_id
 * @property string payment_system_id
 * @property string external
 * @property float balance
 * @property float spend_amount
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Wallet extends Model
{

  use Uuids;

  public $incrementing = false;

  protected $fillable = [
    'user_id',
    'currency_id',
    'payment_system_id',
    'external',
    'balance',
    'spend_amount',
  ];


  /**
    * Создаем денежный кошелек
    * @param User $user
    */
  public static function registerWallets(User $user) {

    $checkExists = $user->getActiveWallet();
    if (!($checkExists instanceof Wallet)) {
      self::setActiveWallet($user);
    }
  }


  /**
    * создаем нужный кошелек
    * @param $user
    * @return mixed
    */
  public static function setActiveWallet($user) {
    if (isFreePromotionSite()) {
      $currency = Currency::where('code', 'FREE_POINTS')
        ->select('id')
        ->first();
      $paymentSystem = PaymentSystem::where('code', 'freePromotion')
        ->select('id')
        ->first();
    } else {
      $currency = Currency::where('code', 'USD')
        ->select('id')
        ->first();
      $paymentSystem = PaymentSystem::where('code', 'free-kassa')
        ->select('id')
        ->first();
    }

    return self::newWallet($user, $currency, $paymentSystem);
  }

  /**
    * @param $user
    * @return mixed
    * deprecated
    */
  public static function setFreePromotionWallet($user) {
    $currency = Currency::where('code', 'FREE_POINTS')->select('id')->first();
    $paymentSystem = PaymentSystem::where('code', 'freePromotion')->select('id')->first();
    return self::newWallet($user, $currency, $paymentSystem);
  }


  /**
    * Новый кошелек
    * @param $user
    * @param $currency
    * @param $paymentSystem
    * @return mixed
    */
  public static function newWallet($user, $currency, $paymentSystem) {
    return self::create([
      'user_id' => $user->id,
      'currency_id' => $currency->id,
      'payment_system_id' => $paymentSystem->id,
    ]);
  }






  // nit: Daan
  public function currency() {
    return $this->belongsTo(Currency::class, 'currency_id');
  }

  public function paymentSystem() {
    return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
  }

  public function transactions() {
    return $this->hasMany(Transaction::class, 'wallet_id');
  }

  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }


  //deprecated ???
  public function deposits() {
    return $this->hasMany(Deposit::class, 'wallet_id');
  }



    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getBalanceAttribute($value)
    {
        /** @var Currency $currency */
        $currency = $this->currency()->first();
        return currencyPrecision($currency->id, $value);
    }

    /**
     * @return float|null
     * @throws \Exception
     */
    public function requestedAmount()
    {
        /** @var TransactionType $transactionWithdrawType */
        $transactionWithdrawType = TransactionType::getByName('withdraw');
        $sum                     = Transaction::where('wallet_id', $this->id)
            ->where('type_id', $transactionWithdrawType->id)
            ->where('approved', 0)
            ->sum('amount');
        $sum = $sum ? $this->getBalanceAttribute($sum) : null;
        return $sum;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function addBonus($amount)
    {
        $commission = TransactionType::getByName('bonus')->commission;
        $this->update(['balance' => $this->balance + $amount - $amount * $commission * 0.01]);
        Transaction::bonus($this, $amount);
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function removeAmount($amount)
    {
        Transaction::penalty($this, $amount);
        $this->update(['balance' => $this->balance - $amount]);
        $this->update(['spend_amount' => $this->spend_amount + $amount]);
        return $this;
    }

    /**
     * @param $amount
     * @param null $source
     * @param null $source_id
     * @return $this
     */
    public function refundAmount($amount, $source = null, $source_id = null)
    {
        Transaction::refund($this, $amount, $source, $source_id);
        $this->update(['balance' => $this->balance + $amount]);
        $this->update(['spend_amount' => $this->spend_amount - $amount]);
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function proceedAccountPurchase($amount)
    {
        Transaction::account_purchase($this, $amount);
        $this->update(['balance' => $this->balance - $amount]);
        $this->update(['spend_amount' => $this->spend_amount + $amount]);
        return $this;
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function addAmountWithoutAccrueToPartner($amount=0.00)
    {
        $this->update(['balance' => $this->balance + $amount]);

        return true;
    }

    /**
     * @param float $amount
     * @param string $partnerAccrueType
     * @return int
     * @throws \Throwable
     */
    public function addAmountWithAccrueToPartner($amount=0.00, $partnerAccrueType='deposit')
    {
        $this->update(['balance' => $this->balance + $amount]);
        return $this->accrueToPartner($amount, $partnerAccrueType);
    }



    /**
     * @param $amount
     * @param $external
     * @throws \Throwable
     */
    public function refill($amount, $external)
    {
        $this->balance += $amount;

        if (!empty($external)) {
            $this->external = $external;
        }

        $this->save();
    }

    /**
     * @param Transaction $transaction
     */
    public function returnFromRejectedWithdrawal(Transaction $transaction)
    {
        $this->update(['balance' => $this->balance + $transaction->amount]);
    }

    /**
     * @param $amount
     * @param $type
     * @return int
     * @throws \Throwable
     */
    public function accrueToPartner($amount, $type)
    {
        /** @var User $user */
        $user           = $this->user;
        $partnerLevels  = $user->getPartnerLevels();

        if (!$partnerLevels) {
            return 0;
        }

        foreach ($partnerLevels as $level) {
            if ($type == 'refill') {
                $percent = Referral::getOnLoad($level);
            } elseif ($type == 'deposit') {
                $percent = Referral::getOnProfit($level);
            } elseif ($type == 'task') {
                $percent = Referral::getOnTask($level);
            } else {
                $percent = 0;
            }

            if ($percent <= 0) {
                continue;
            }

            $partnerAmount  = $amount * $percent / 100;
            /** @var User $partner */
            $partner        = $user->getPartnerOnLevel($level);

            if (empty($partner)) {
                continue;
            }

            $partnerWallets = $partner->wallets()->get();

            if ($partnerWallets->count() == 0) {
                /** @var Wallet $newPartnerWallet */
                $newPartnerWallet = self::newWallet($partner, $this->currency, $this->paymentSystem);
                $newPartnerWallet->referralRefill($partnerAmount, $this, $type);
            }

            $summaryAmount = 0;

            /** @var Wallet $partnerWallet */
            foreach ($partnerWallets as $partnerWallet) {
                if ($partnerWallet->currency == $this->currency && $partnerWallet->paymentSystem == $this->paymentSystem) {
                    $partnerWallet->referralRefill($partnerAmount, $this, $type);
                    $summaryAmount += $partnerAmount;


                    $notificationActive = $partner->socialMeta()
                        ->where('s_key', 'settings_notifications_referral '.$level.'_level')
                        ->where('s_value', 1)
                        ->count();

                    if ($notificationActive > 0) {
                        $partner->sendNotification('affiliate_earnings', [
                            'amount'            => $partnerAmount,
                            'receiveWallet'     => $partnerWallet,
                            'sender'            => $user,
                            'receive'           => $partner,
                            'level'             => $level,
                        ]);
                    }

                    break;
                }
            }
        }
        return isset($summaryAmount) ? $summaryAmount : 0;
    }

    /**
     * @param $amount
     * @param $referral
     * @param $type
     */
    public function referralRefill($amount, $referral, $type)
    {
        $this->update(['balance' => $this->balance + $amount]);

        if ($type == 'refill') {
            Transaction::partner($this, $amount, $referral);
        } elseif ($type == 'deposit') {
            Transaction::partner($this, $amount, $referral);
        } elseif ($type == 'task') {
            Transaction::partner($this, $amount, $referral);
        }

        $data = [
            'refill_amount' => $amount,
            'balance'       => $this->balance,
            'currency'      => $this->currency,
            'type'          => $type
        ];
        $this->user->sendNotification('partner_accrue', $data);
    }





    /**
     * @param $user
     * @return mixed
     */
    public static function setReferralWallet($user)
    {
        $currency = Currency::where('code', 'REF_RUB')->first();
        $paymentSystem = PaymentSystem::where('code', 'unitpay')->first();
        return self::newWallet($user, $currency, $paymentSystem);
    }



    public function getDiscount($out_user = null): int
    {
        $user = $out_user ? $out_user : \Auth::user();
        $discount = 0;
        if($this->spend_amount >= 1000 and $this->spend_amount < 3000 ){
            $discount = 1;
        }
        if($this->spend_amount >= 3000 and $this->spend_amount < 5000 ){
            $discount = 3;
        }
        if($this->spend_amount >= 5000 and $this->spend_amount < 10000 ){
            $discount = 5;
        }
        if($this->spend_amount >= 10000 and $this->spend_amount < 25000 ){
            $discount = 7;
        }
        if($this->spend_amount >= 25000 and $this->spend_amount < 50000 ){
            $discount = 10;
        }
        if($this->spend_amount >= 50000 and $this->spend_amount < 100000 ){
            $discount = 12;
        }
        if($this->spend_amount >= 100000 and $this->spend_amount < 200000 ){
            $discount = 15;
        }
            if($this->spend_amount >= 200000 and $this->spend_amount < 350000 ){
            $discount = 18;
        }
        if($this->spend_amount >= 35000 and $this->spend_amount < 500000 ){
            $discount = 21;
        }
        if($this->spend_amount >= 500000 and $this->spend_amount < 750000 ){
            $discount = 24;
        }
        if($this->spend_amount >= 750000 and $this->spend_amount < 1500000 ){
            $discount = 27;
        }
        if($this->spend_amount >= 1500000){
            $discount = 30;
        }
        if($user->is_premium){
            $discount += 10;
        }
        return $discount;
    }

    public function getNextDiscountPercent()
    {
        $discount = $this->getDiscount();
        if ($discount < 1) {
            $next_step = 1;
        } elseif ($discount >= 1 && $discount < 3) {
            $next_step = 3;
        } elseif ($discount >= 3 && $discount < 5) {
            $next_step = 5;
        } elseif ($discount >= 5 && $discount < 7) {
            $next_step = 7;
        } elseif ($discount >= 7 && $discount < 10) {
            $next_step = 10;
        } elseif ($discount >= 10 && $discount < 12) {
            $next_step = 12;
        } elseif ($discount >= 12 && $discount < 15) {
            $next_step = 15;
        } elseif ($discount >= 15 && $discount < 18) {
            $next_step = 18;
        } elseif ($discount >= 18 && $discount < 21) {
            $next_step = 21;
        } elseif ($discount >= 21 && $discount < 24) {
            $next_step = 24;
        } elseif ($discount >= 24 && $discount < 27) {
            $next_step = 27;
        } elseif ($discount >= 27 && $discount < 30) {
            $next_step = 30;
        } else {
            $next_step = '';
        }
        return $next_step;
    }


    public function getNextDiscountAmount()
    {
        $discount = $this->getDiscount();
        if ($discount < 1) {
            $next_step = 1000;
        } elseif ($discount >= 1 && $discount < 3) {
            $next_step = 3000;
        } elseif ($discount >= 3 && $discount < 5) {
            $next_step = 5000;
        } elseif ($discount >= 5 && $discount < 7) {
            $next_step = 10000;
        } elseif ($discount >= 7 && $discount < 10) {
            $next_step = 25000;
        } elseif ($discount >= 10 && $discount < 12) {
            $next_step = 50000;
        } elseif ($discount >= 12 && $discount < 15) {
            $next_step = 100000;
        } elseif ($discount >= 15 && $discount < 18) {
            $next_step = 200000;
        } elseif ($discount >= 18 && $discount < 21) {
            $next_step = 350000;
        } elseif ($discount >= 21 && $discount < 24) {
            $next_step = 500000;
        } elseif ($discount >= 24 && $discount < 27) {
            $next_step = 750000;
        } elseif ($discount >= 27 && $discount < 30) {
            $next_step = 1500000;
        } else {
            $next_step = '';
        }
        return $next_step;
    }


    public function apply_discount($sum, User $user=null)
    {
        $percent = 100 - $this->getDiscount($user);
        return $this->priceRound(($sum / 100 ) * $percent);
    }

    /**
     * @param float $amount
     * @return void
     */
    public function replenishFreePromotions(float $amount)
    {
        if ($this->paymentSystem->code === 'freePromotion') {
            Transaction::enter($this, $amount);
            $this->update(['balance' => $this->balance + $amount]);
        }
    }

    /**
     * @param float $amount
     * @return void
     */
    public function withdrawFreePromotions(float $amount)
    {
        if ($this->paymentSystem->code === 'freePromotion') {
            Transaction::withdraw($this, $amount);
            $this->update(['spend_amount' => $this->spend_amount + $amount]);
        }
    }

    private function priceRound($price) {
        return (floor($price * 100) / 100.0);
    }
}
