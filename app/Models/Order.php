<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 *
 * @property Packet packet
 * @property Subscription subscription
 * @property User user
 * @property string user_id
 * @property string order_id
 * @property string name
 * @property string link
 * @property integer quantity
 * @property float price
 * @property string status
 * @property string packet_id
 * @property string jap_id
 * @property string jap_status
 * @property integer subscription_id
 * @property string username
 * @property integer min
 * @property integer max
 * @property string expiry
 */
class Order extends Model
{
    protected $table = 'orders';

    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'order_id',
        'user_id',
        'name',
        'link',
        'quantity',
        'price',
        'status',
        'packet_id',
        'jap_id',
        'jap_status',
        'subscription_id',
        'username',
        'min',
        'max',
        'expiry',
        'type',
        'product_id',
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
    public function packet()
    {
        return $this->belongsTo(Packet::class, 'packet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function createUniqueOrderId()
    {
        if (empty($this->order_id)) {
            $orderId = null;
            $exist = True;
            while ($exist) {
                $orderId = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 7);
                $exist = self::where('order_id', $orderId)->first();
            }
            $this->order_id = $orderId;
        }
    }
}
