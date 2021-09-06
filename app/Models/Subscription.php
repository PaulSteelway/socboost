<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Subscription
 * @package App\Models
 * @version April 20, 2020, 6:14 pm UTC
 *
 * @property \App\Models\Currency currency
 * @property \App\Models\Packet packet
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection orders
 * @property integer id
 * @property string user_id
 * @property string currency_id
 * @property string type
 * @property integer period
 * @property string date_at
 * @property integer packet_id
 * @property string subscription_id
 * @property string status
 * @property string payment_method
 * @property string ip
 * @property string username
 * @property integer posts
 * @property integer qty_min
 * @property integer qty_max
 * @property string note
 */
class Subscription extends Model
{

    public $table = 'subscriptions';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'user_id',
        'currency_id',
        'type',
        'period',
        'date_at',
        'packet_id',
        'subscription_id',
        'status',
        'payment_method',
        'ip',
        'username',
        'posts',
        'qty_min',
        'qty_max',
        'note'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'string',
        'currency_id' => 'string',
        'type' => 'string',
        'period' => 'integer',
        'date_at' => 'date',
        'packet_id' => 'integer',
        'subscription_id' => 'string',
        'status' => 'string',
        'payment_method' => 'string',
        'ip' => 'string',
        'username' => 'string',
        'posts' => 'integer',
        'qty_min' => 'integer',
        'qty_max' => 'integer',
        'note' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'currency_id' => 'required',
        'type' => 'required',
        'period' => 'required',
        'status' => 'required',
//        'payment_method' => 'required',
//        'ip' => 'required'
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
    public function packet()
    {
        return $this->belongsTo(\App\Models\Packet::class, 'packet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'subscription_id');
    }
}
