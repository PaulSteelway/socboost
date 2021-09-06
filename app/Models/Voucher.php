<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Voucher
 * @package App\Models
 * @version March 14, 2020, 6:00 pm UTC
 *
 * @property Currency currency
 * @property User user
 * @property integer id
 * @property string code
 * @property integer amount
 * @property string currency_id
 * @property string user_id
 */
class Voucher extends Model
{
    public $table = 'vouchers';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'code',
        'amount',
        'currency_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'amount' => 'integer',
        'currency_id' => 'string',
        'user_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required',
        'amount' => 'required',
        'currency_id' => 'required|exists:currencies,id',
        'user_id' => 'nullable|exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return string
     */
    public static function createVoucherCode()
    {
        $code = null;
        $exist = True;
        while ($exist) {
            $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 7);
            $exist = self::where('code', $code)->first();
        }
        return $code;
    }
}
