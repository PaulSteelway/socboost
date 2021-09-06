<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Package
 * @package App\Models
 * @version March 26, 2020, 10:19 pm UTC
 *
 * @property \App\Models\Category category
 * @property string name
 * @property integer qty
 * @property number price
 * @property integer category_id
 */
class Package extends Model
{

    public $table = 'packages';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'qty',
        'price',
        'category_id',
        'jap_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'jap_id' => 'string',
        'name' => 'string',
        'qty' => 'integer',
        'price' => 'float',
        'category_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'qty' => 'required',
        'price' => 'required',
        'category_id' => 'required',
        'jap_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
