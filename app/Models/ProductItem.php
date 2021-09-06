<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductItem
 * @package App\Models
 * @version May 19, 2020, 8:43 pm UTC
 *
 * @property \App\Models\Product product
 * @property string name
 * @property integer qty
 * @property number price
 * @property integer product_id
 * @property string jap_id
 */
class ProductItem extends Model
{
    use SoftDeletes;

    public $table = 'product_items';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'username',
        'password',
        'product_id',
        'user_id',
        'port',
        'ip',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'username' => 'string',
        'password' => 'string',
        'product_id' => 'integer',
        'port' => 'integer',
        'ip' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'keys' => 'required|array',
        'keys.0' => 'required|string|in:username',
        'keys.1' => 'required|string|in:password',
        'values' => 'required|array',
        'password_field' => 'required',
        'product_id' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
