<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Deposit
 * @package App\Models
 *
 * @property string id
 * @property string currency_id валюта
 * @property string wallet_id кошелек с которого создается депозит, на него же возвращается при закрытии
 * @property string user_id
 * @property string rate_id ИД тарифного плана
 * @property float daily процент ежедневных начислений
 * @property float overall процент на начальную сумму при закрытии
 * @property int duration продолжительность действия депозита (в днях) равно кол-ву ежедневных начислений
 * @property float payout выплата начальной суммы в процентах
 * @property float invested начальная сумма депозита
 * @property float balance текущий баланс (с учетом начислений)
 * @property int reinvest ставка реинвестирования
 * @property int autoclose закрываем депозит по графику
 * @property int active статус
 * @property string condition последнее действие
 * @property Carbon datetime_closing
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Deposit extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'currency_id',
        'user_id',
        'wallet_id',
        'rate_id',
        'daily',
        'overall',
        'duration',
        'payout',
        'invested',
        'balance',
        'reinvest',
        'autoclose',
        'active',
        'condition',
        'datetime_closing',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'deposit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rate()
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    /**
     * @return mixed
     */
    public function paymentSystem()
    {
        return $this->wallet->first()->paymentSystem();
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getBalanceAttribute($value)
    {
        if (isset($this->currency->code)) {
            return currencyPrecision($this->currency->id, $value);
        };
        return $value;
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getInvestedAttribute($value)
    {
        if (isset($this->currency->code)) {
            return currencyPrecision($this->currency->id, $value);
        };
        return $value;
    }

    /**
     * @param $field
     * @return Deposit|null
     * @throws \Exception
     */
    public static function addDeposit($field)
    {
        /** @var User $user */
        $user     = isset($field['user']) ? $field['user'] : Auth::user();
        /** @var Wallet $wallet */
        $wallet   = $user->wallets()->where('id', $field['wallet_id'])->first();
        $amount   = abs($field['amount']);
        $reinvest = array_key_exists('reinvest', $field) ? abs($field['reinvest']) : 0;
        /** @var Rate $rate */
        $rate     = Rate::find($field['rate_id']);

        if ($rate->currency_id != $wallet->currency_id) {
            throw new \Exception('Wrong currency ID');
        }

        if ($amount < $rate->min || $amount > $rate->max) {
            throw new \Exception('Wrong deposit amount. Less or greater than in tariff plan.');
        }

        $deposit                    = new Deposit;
        $deposit->rate_id           = $rate->id;
        $deposit->currency_id       = $rate->currency_id;
        $deposit->wallet_id         = $wallet->id;
        $deposit->user_id           = $user->id;
        $deposit->invested          = $amount;
        $deposit->daily             = $rate->daily;
        $deposit->overall           = $rate->overall;
        $deposit->duration          = $rate->duration;
        $deposit->payout            = $rate->payout;
        $deposit->balance           = $amount;
        $deposit->reinvest          = $reinvest;
        $deposit->autoclose         = $rate->autoclose;
        $deposit->condition         = 'create';
        $deposit->datetime_closing  = now()->addDays($rate->duration);
        $deposit->created_at        = isset($field['created_at']) ? $field['created_at'] : now();

        $transaction = $deposit->save()
            ? Transaction::createDeposit($deposit)
            : null;

        if (null != $transaction && $deposit->wallet->removeAmount($amount)) {
            $transaction->update(['approved' => true]);
            $deposit->update(['active' => true]);

            // send notification to user
            $data = [
                'deposit' => $deposit
            ];
            $deposit->user->sendNotification('deposit_opened', $data);
            return ($deposit->createSequence())
                ? $deposit
                : null;
        };
        throw new \Exception("Transaction start or wallet error! ".print_r($field,true));
    }

    /**
     * @return bool
     */
    public function createSequence()
    {
        /** @var Rate $rate */
        $rate = $this->rate()->first();

        if (!is_int($this->duration) || $this->duration < 1) {
            return false;
        }

        for ($i = 1; $i <= $rate->duration; $i++) {
            $depositQueue = new DepositQueue();
            $depositQueue->deposit_id = $this->id;
            $depositQueue->setTypeAccrue();
            $depositQueue->setAvailableAt(now()->addDays($i));
            $depositQueue->save();
        };

        if ($this->autoclose) {
            $depositQueue = new DepositQueue();
            $depositQueue->deposit_id = $this->id;
            $depositQueue->setTypeClosing();
            $depositQueue->setAvailableAt(now()->addDays($rate->duration)->addSeconds('30'));
            $depositQueue->save();
        };

        return true;
    }

    /**
     * @param DepositQueue $depositQueue
     * @return bool
     * @throws \Throwable
     */
    public function accrue(DepositQueue $depositQueue)
    {
        $depositQueue->setIsDone()->save();

        if ($this->condition == 'blocked') {
            throw new \Exception('Accrue failed because deposit blocked');
        }

        // проверяем статус депозита и оплату
        if ($this->condition == 'create' && $this->investTransaction()->approved) {
            $this->update(['condition' => 'onwork']);
        }

        $countTransactions = Transaction::where('deposit_id', $this->id)
                ->where('approved', true)
                ->count() - 1; // минус 1 это открытие

        if ($this->duration < $countTransactions || $this->condition != 'onwork') {
            throw new \Exception("error status deposit!");
        }

        /** @var Wallet $wallet */
        $wallet = $this->wallet()->first();

        /** @var User $user */
        $user = $this->user()->first();

        $reinvest = $this->reinvest ?? 0;
        $amountReinvest = $this->balance * $this->daily * 0.01 * $reinvest * 0.01;
        $amountToWallet = $this->balance * $this->daily * 0.01 - $amountReinvest;
        $dividend = Transaction::dividend($wallet, $amountToWallet);

        $wallet->addAmountWithAccrueToPartner($amountToWallet, 'deposit');
        $this->addBalance($amountReinvest);

        $dividend->update(['approved' => true]);

        // send notification to user
        $data = [
            'dividend' => $dividend,
            'deposit'  => $this
        ];
        $user->sendNotification('deposit_accrued', $data);
        return true;
    }

    /**
     * @param DepositQueue $depositQueue
     * @return bool
     * @throws \Exception
     */
    public function close(DepositQueue $depositQueue)
    {
        $depositQueue->setIsDone()->save();

        if ($this->condition != 'onwork' || !$this->active) {
            throw new \Exception("failed close");
        }

        /** @var Wallet $wallet */
        $wallet = $this->wallet()->first();

        /** @var User $user */
        $user = $this->user()->first();

        if ($this->overall) {
            $amountOverall = $this->invested * $this->overall * 0.01;
            $transactionOverall = Transaction::dividend($wallet, $amountOverall);

            if ($transactionOverall->update([
                'approved' => $wallet->addAmountWithoutAccrueToPartner($amountOverall)
            ])) {
                $this->update(['condition' => 'overall']);
            } else {
                throw new \Exception("failed overall!");
            }
        }

        $amount = $this->balance;
        $closeTransaction = Transaction::closeDeposit($this, $amount);

        if (!$wallet->addAmountWithoutAccrueToPartner($amount)) {
            throw new \Exception("deposit not close!");
        }

        $closeTransaction->update(['approved' => true]);
        $this->update(['condition' => 'closed']);
        $this->update(['active' => false]);

        // send notification to user
        $data = [
            'deposit' => $this
        ];
        $user->sendNotification('deposit_closed', $data);
        return true;
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function addBalance($amount=0.00)
    {
        return $this->update(['balance' => $this->balance + $amount]);
    }

    /**
     * @return mixed
     */
    public function investTransaction()
    {
        $typeId = TransactionType::getByName('create_dep')->id;

        return Transaction::where([
            'wallet_id' => $this->wallet_id,
            'type_id' => $typeId,
            'deposit_id' => $this->id,
        ])->orderBy('created_at')->first();
    }

    /**
     * @return bool
     */
    public function block()
    {
        if ($this->active != true || $this->condition == 'blocked' || $this->condition == 'closed') {
            return false;
        }

        $this->condition = 'blocked';
        $this->active = false;

        $this->save();
        return true;
    }

    /**
     * @return bool
     */
    public function unblock()
    {
        if ($this->active != false || $this->condition != 'blocked') {
            return false;
        }

        $this->active    = true;
        $this->condition = 'onwork';

        $this->save();
        return true;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function closedBalances(): array
    {
        $deposits = Currency::join('deposits', 'currencies.id', '=', 'deposits.currency_id')
            ->where('deposits.condition', 'closed')
            ->select('currencies.code', 'deposits.balance')
            ->get();

        $balances = Currency::balances();

        foreach ($deposits as $item) {
            $balances[$item->code] = key_exists($item->code, $balances)
                ? $balances[$item->code] + $item->balance
                : $item->balance;
        }
        return $balances;
    }
}
