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
 * Class Rate
 * @package App\Models
 *
 * @property string id
 * @property string currency_id
 * @property string name - название тарифного плана.
 * @property float min - минимальная сумма инвестиций.
 * @property float max - максимальная сумма инвестиций.
 * @property float daily - процент ежедневных начислений, может не задаваться.
 * @property float overall - процент на начальную сумму при закрытии, может не задаваться.
 * @property integer duration - продолжительность действия депозита (в днях) равно кол-ву ежедневных начислений.
 * @property float payout - выплата начальной суммы в процентах.
 * @property integer reinvest - активна ли возможность реинвестировоать в депозит.
 * @property integer autoclose - автозакрытие депозитов по этому плану.
 * @property integer active - активен ли тарифный план.
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Rate extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'currency_id',
        'name',
        'min',
        'max',
        'daily',
        'overall',
        'duration',
        'payout',
        'reinvest',
        'autoclose',
        'active'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class,'rate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'rate_id');
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getMinAttribute($value)
    {
        return isset($this->currency->code)
            ? currencyPrecision($this->currency->id, $value)
            : $value;
    }

    /**
     * @param $value
     * @return float
     * @throws \Exception
     */
    public function getMaxAttribute($value)
    {
        return isset($this->currency->code)
            ? currencyPrecision($this->currency->id, $value)
            : $value;
    }
}
