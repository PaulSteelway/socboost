<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Models\UserTasks\Tasks;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 * @package App\Models
 *
 * @property string id
 * @property string name
 * @property string code
 * @property integer precision
 * @property string symbol
 * @property string|null currency_id
 */
class Currency extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'precision',
        'symbol',
        'currency_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paymentSystems()
    {
        return $this->belongsToMany(PaymentSystem::class, 'currency_payment_system', 'currency_id', 'payment_system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rates()
    {
        return $this->hasMany(Rate::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'currency_id');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function balances(): array
    {
        return cache()->remember('admin.currency.nullBalance', 60, function () {
            foreach (self::all() as $currency) {
                $balances[$currency->code] = 0.00;
            }
            return isset($balances)? $balances : [];
        });
    }
}
