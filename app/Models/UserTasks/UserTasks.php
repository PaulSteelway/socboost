<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UsersSocialMeta;
use App\Models\Wallet;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTasks
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property User user_id
 * @property Tasks task_id
 * @property integer status
 * @property Carbon completed_at
 * @property integer active
 * @property Carbon start_datetime
 * @property Carbon end_datetime
 * @property integer payed
 */
class UserTasks extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'task_id',
        'status',
        'completed_at',
        'active',
        'start_datetime',
        'end_datetime',
        'details',
        'payed',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array'
    ];


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
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTaskActions()
    {
        return $this->hasMany(UserTaskActions::class, 'user_task_id');
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getRewardAmountAttribute($value)
    {
        /** @var Tasks $task */
        $task = $this->task()->first();

        if (null === $task) {
            throw new \Exception('Main task not found');
        }

        /** @var Currency $currency */
        $currency = $task->currency()->first();

        if (null === $currency) {
            throw new \Exception('Currency not found');
        }

        if (isset($currency->code)) {
            return currencyPrecision($currency->id, $value);
        };
        return $value;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function sendReward()
    {
        /** @var User $user */
        $user = $this->user()->first();
        /** @var Tasks $task */
        $task = $this->task()->first();

        if (null === $user || null === $task) {
            \Log::error('User or task not found for getting reward');
            return false;
        }

        if (0 == $task->reward_amount) {
            \Log::info('Task reward amount is 0');
            return false;
        }

        /** @var Wallet $wallet */
        $wallet = $user->wallets()
            ->where('currency_id', $task->reward_currency_id)
            ->where('payment_system_id', $task->reward_payment_system_id)
            ->first();

        if (null === $wallet) {
            \Log::error('Wallet not found for task reward.');
            return false;
        }

        $rewardAmount = (float) $task->reward_amount;

        /*
         * Coefficients
         */
        $acceptedDate       = Carbon::parse($this->start_datetime);
        $now                = now();
        $diffInMinutes      = abs($acceptedDate->diffInMinutes($now));
        /** @var TaskCoefficients $searchCoefficient */
        $searchCoefficient  = $task->coefficients()
            ->where('min_minutes', '>=', $diffInMinutes)
            ->where('max_minutes', '<=', $diffInMinutes)
            ->orderBy('reward_coefficient')
            ->limit(1)
            ->first();

        if (null !== $searchCoefficient) {
            \Log::info('Found coefficient for user task '.$this->id.', with id'.$searchCoefficient->id);
            $rewardAmount *= (float) $searchCoefficient->reward_coefficient;
        }

        $summaryCommissions = $wallet->addAmountWithAccrueToPartner($rewardAmount, 'task');
        $dividend           = Transaction::dividend($wallet, $rewardAmount);

        if (config('app.env') == 'develop') {
            \Log::info('Summary referral commissions is '.$summaryCommissions.', and reward amount for task is '.$rewardAmount);
        }

        if (null === $dividend) {
            \Log::error('Can not create dividend transaction for this task');
            return false;
        }

        $user->sendNotification('task_completed', [
            'task'   => $task,
            'user'   => $user
        ], 3);

        $notificationSet = UsersSocialMeta::getValue($user, 'set_settings_notifications_rewards set', null);

        if (null !== $notificationSet) {
            $user->sendNotification('task_reward', [
                'amount' => $rewardAmount,
                'wallet' => $wallet,
                'user'   => $user
            ], 6);
        }

        try {
            $withdrawalRequest = Transaction::withdraw($wallet, $rewardAmount);
        } catch(\Exception $e) {
            \Log::error('Can not handle instant payment, after getting task reward. '.$e->getMessage());
            return false;
        }

        \Log::info('Processed instant payments: '.$withdrawalRequest->id);

        return true;
    }
}
