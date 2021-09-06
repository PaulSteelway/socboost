<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Promocode
 * @package App\Models
 * @version November 6, 2019, 9:53 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection users
 * @property string code
 * @property number reward
 * @property string data
 * @property boolean is_disposable
 * @property string|\Carbon\Carbon expires_at
 */
class Promocode extends Model
{


    public $table = 'promocodes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public $timestamps = false;



    public $fillable = [
        'code',
        'reward',
        'from_amount',
        'data',
        'is_disposable',
        'expires_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'reward' => 'float',
        'from_amount' => 'float',
        'data' => 'array',
        'is_disposable' => 'integer',
        'expires_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'reward' => 'required',
        'data' => 'required',
        'is_disposable' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'promocode_user');
    }
}
