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
 * Class Transaction
 * @package App\Models
 *
 * @property string id
 * @property string type_id - тип операции
 * @property string user_id
 * @property string rate_id - тарифный план, если это депозитная транзакция.
 * @property string deposit_id
 * @property string wallet_id
 * @property string payment_system_id
 * @property float amount
 * @property string source - кошелек реферала пользователя, если это партнерская транзакция.
 * @property string result - ответ платежной системы.
 * @property string batch_id - ИД операции в платежной системе.
 * @property bool approved
 * @property float commission
 * @property Carbon created_at
 * @property Carbon updated_at
 * @method static create(array $transactionData)
 */
class Transaction extends Model
{
    use Uuids;
    public const BOOSTER_SITE = 1;
    public const FREE_BOOSTER_SITE = 2;
    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'type_id',
        'user_id',
        'currency_id',
        'rate_id',
        'deposit_id',
        'wallet_id',
        'payment_system_id',
        'amount',
        'source',
        'result',
        'batch_id',
        'approved',
        'commission',
        'created_at',
        'promocode_id',
        'site_id',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'promocode_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rate()
    {
        return $this->belongsTo(Rate::class, 'rate_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'deposit_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentSystem()
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getAmountAttribute($value)
    {
        if (null == $this->currency_id) {
            return $value;
        }

        return currencyPrecision($this->currency_id, $value);
    }

    /**
     * @param $wallet
     * @param $amount
     * @return mixed
     */
    public static function enter($wallet, $amount, $promocode_id = null)
    {
        $type = TransactionType::getByName('enter');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => $type->commission,
            'user_id' => $wallet->user->id,
            'currency_id' => $wallet->currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
        ]);
        if($promocode_id){
            $transaction->promocode_id = $promocode_id;
        }
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $paymentSystem
     * @param $amount
     * @param $currency
     * @param null $promocode_id
     * @return Transaction|null
     */
    public static function enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id = null)
    {
        $type = TransactionType::getByName('enter');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => $type->commission,
            'user_id' => $wallet->user->id,
            'currency_id' => $currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $paymentSystem->id,
            'amount' => $amount,
        ]);
        if ($promocode_id) {
            $transaction->promocode_id = $promocode_id;
        }
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @return Transaction|null
     * @throws \Exception
     */
    public static function withdraw(Wallet $wallet, float $amount)
    {
        $amount         = (float) abs($amount);
        /** @var TransactionType $type */
        $type           = TransactionType::getByName('withdraw');
        /** @var User $user */
        $user           = $wallet->user()->first();
        /** @var Currency $currency */
        $currency       = $wallet->currency()->first();
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem  = $wallet->paymentSystem()->first();

        if (null === $type || null === $user || null === $currency || null === $paymentSystem) {
            return null;
        }

        $commission           = $type->commission;
        $amountWithCommission = $amount / ((100 - $commission) * 0.01);

        $psMinimumWithdrawArray = @json_decode($paymentSystem->minimum_withdraw, true);
        $psMinimumWithdraw      = isset($psMinimumWithdrawArray[$currency->code])
            ? $psMinimumWithdrawArray[$currency->code]
            : 0;

        if ($amount+$commission < $psMinimumWithdraw) {
            throw new \Exception(__('Minimum withdraw amount is ').$psMinimumWithdraw.$currency->symbol);
        }

        /** @var Transaction $transaction */
        $transaction = self::create([
            'type_id'           => $type->id,
            'commission'        => $type->commission,
            'user_id'           => $user->id,
            'currency_id'       => $currency->id,
            'wallet_id'         => $wallet->id,
            'payment_system_id' => $paymentSystem->id,
            'amount'            => $amountWithCommission,
            'approved'          => false,
        ]);

        $wallet->update([
            'balance' => $wallet->balance - $amountWithCommission
        ]);

        return $transaction->save()
            ? $transaction
            : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @param Currency $currency
     * @param string $source
     * @return null
     */
    public static function bonus($wallet, $amount, $currency = null, $source = null)
    {
        $type = TransactionType::getByName('bonus');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => $type->commission,
            'user_id' => $wallet->user->id,
            'currency_id' => empty($currency) ? $wallet->currency->id : $currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
            'source' => $source,
            'approved' => true,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @param $referral
     * @return null
     */
    public static function partner($wallet, $amount, $referral)
    {
        $type = TransactionType::getByName('partner');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $wallet->user->id,
            'currency_id' => $wallet->currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
            'source' => $referral->id,
            'approved' => true,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @param null $referral
     * @return null
     */
    public static function dividend($wallet, $amount, $referral = null)
    {
        $type = TransactionType::getByName('dividend');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $wallet->user->id,
            'currency_id' => $wallet->currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
            'source' => null !== $referral
                ? $referral->id
                : null,
            'approved' => true,
        ]);

        $referralName = null !== $referral ? $referral->name : '';
        $referralId   = null !== $referral ? $referral->id : '';

        return $transaction->save() ? $transaction : null;

    }

    /**
     * @param $deposit
     * @return null
     */
    public static function createDeposit($deposit)
    {
        $type = TransactionType::getByName('create_dep');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $deposit->user->id,
            'currency_id' => $deposit->currency->id,
            'rate_id' => $deposit->rate->id,
            'deposit_id' => $deposit->id,
            'wallet_id' => $deposit->wallet->id,
            'payment_system_id' => $deposit->paymentSystem->id,
            'amount' => $deposit->invested,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $deposit
     * @param $amount
     * @return null
     */
    public static function closeDeposit($deposit, $amount)
    {
        $type = TransactionType::getByName('close_dep');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $deposit->user->id,
            'currency_id' => $deposit->currency->id,
            'rate_id' => $deposit->rate->id,
            'deposit_id' => $deposit->id,
            'wallet_id' => $deposit->wallet->id,
            'payment_system_id' => $deposit->paymentSystem->id,
            'amount' => $amount,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @return null
     */
    public static function penalty($wallet, $amount)
    {
        $type = TransactionType::getByName('penalty');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $wallet->user_id,
            'currency_id' => $wallet->currency->id,
            'rate_id' => null,
            'deposit_id' => null,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @param null $source
     * @param null $result
     * @return Transaction|null
     */
    public static function refund($wallet, $amount, $source = null, $result = null)
    {
        $type = TransactionType::getByName('refund');
        $transaction = self::create([
            'type_id' => $type->id,
            'user_id' => $wallet->user_id,
            'currency_id' => $wallet->currency->id,
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'commission' => 0,
            'source' => $source,
            'result' => $result,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param $wallet
     * @param $amount
     * @return null
     */
    public static function account_purchase($wallet, $amount)
    {
        $type = TransactionType::getByName('account_purchase');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $wallet->user_id,
            'currency_id' => $wallet->currency->id,
            'rate_id' => null,
            'deposit_id' => null,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->paymentSystem->id,
            'amount' => $amount,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param string $type
     * @param string $role
     * @return array
     * @throws \Exception
     */
    public static function transactionBalances(string $type, string $role = ''): array
    {
        $type = TransactionType::getByName($type);

        if ($role) {
            $transactions = User::role($role)->join('transactions', function ($join) use ($type) {
                $join->on('users.id', '=', 'transactions.user_id')
                    ->where('transactions.approved', true)->where('transactions.type_id', $type->id);
            })->join('currencies', 'currencies.id', '=',
                'transactions.currency_id')->select('currencies.code', 'transactions.amount')->get();
        } else {
            $transactions = Currency::join('transactions', function ($join) use ($type) {
                $join->on('currencies.id', '=', 'transactions.currency_id')
                    ->where('transactions.approved', true)->where('transactions.type_id', $type->id);
            })->select('currencies.code', 'transactions.amount')->get();
        }

        $balances = Currency::balances();

        foreach ($transactions as $item) {
            $balances[$item->code] = key_exists($item->code, $balances)
                ? $balances[$item->code] + $item->amount
                : $item->amount;
        }

        return $balances;

    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function commissionBalances(): array
    {
        $balances = [];
        $bonus = Transaction::transactionBalances('bonus');
        $enter = Transaction::transactionBalances('enter');
        $withdraw = Transaction::transactionBalances('withdraw');

        foreach (Currency::all() as $currency) {
            $balances[$currency->code] = $bonus[$currency->code] * TransactionType::getByName('bonus')->commission * 0.01 + $enter[$currency->code] * TransactionType::getByName('enter')->commission * 0.01 + $withdraw[$currency->code] * TransactionType::getByName('withdraw')->commission * 0.01;
        }
        return $balances;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->approved == 1;
    }

    /**
     * @param Wallet $wallet
     * @param $amount
     * @param $source
     * @return Transaction|null
     */
    public static function referral(Wallet $wallet, $amount, $source)
    {
        $type = TransactionType::getByName('referral');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => 0,
            'user_id' => $wallet->user_id,
            'currency_id' => $wallet->currency_id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $wallet->payment_system_id,
            'amount' => $amount,
            'source' => $source,
            'approved' => true,
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param Subscription $subscription
     * @param string $paymentSystemCode
     * @return self|null
     * @throws \Exception
     */
    public static function subscriptionOrder(Subscription $subscription, $paymentSystemCode = 'unitpay')
    {
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = PaymentSystem::where('code', $paymentSystemCode)->first();

        /** @var Wallet $wallet */
        $wallet = $subscription->user->getActiveWallet();
        if (empty($wallet)) {
            throw new \Exception(__('User wallet not found'));
        }

        $amount = $wallet->apply_discount($subscription->packet->price * $subscription->posts * $subscription->qty_max);
        $currency = $subscription->currency;
        if ($currency->code == 'USD') {
            $amount = convertRubToUsd($amount);
        }

        $type = TransactionType::getByName('subscription');
        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => $type->commission,
            'user_id' => $subscription->user_id,
            'currency_id' => $currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $paymentSystem->id,
            'amount' => $amount,
            'source' => $subscription->id
        ]);
        return $transaction->save() ? $transaction : null;
    }

    /**
     * @param Subscription $subscription
     * @param string $paymentSystemCode
     * @param $amount
     * @return self|null
     */
    public static function subscriptionPremiumAccount(Subscription $subscription, $paymentSystemCode = 'unitpay', $amount = null)
    {
        /** @var TransactionType $type */
        $type = TransactionType::getByName('subscription');

        /** @var Currency $currency */
        $currency = $subscription->currency;

        /** @var Wallet $wallet */
        $wallet = $subscription->user->getActiveWallet();

        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = PaymentSystem::where('code', $paymentSystemCode)->first();

        if (empty($amount)) {
            $amount = $currency->code == 'USD' ? convertRubToUsd(1) : 1;
        }

        $transaction = self::create([
            'type_id' => $type->id,
            'commission' => $type->commission,
            'user_id' => $subscription->user_id,
            'currency_id' => $currency->id,
            'wallet_id' => $wallet->id,
            'payment_system_id' => $paymentSystem->id,
            'amount' => $amount,
            'source' => $subscription->id
        ]);
        return $transaction->save() ? $transaction : null;
    }

    public static function notifyFailTransaction($payment_system, $transaction_id): void {
        $text = "Попытка повторной оплаты счёта " . $payment_system .
        "\nНомер заказа: " . $transaction_id;

        $text = urlencode($text);
        
        file_get_contents("http://api.telegram.org/bot" . config('money.telegram_key') . "/sendMessage?parse_mode=HTML&chat_id=-320089166&text=" . $text);
    }
}
