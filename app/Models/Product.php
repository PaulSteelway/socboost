<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Product
 * @package App\Models
 * @version May 19, 2020, 8:43 pm UTC
 *
 * @property \App\Models\AccountCategory accountCategory
 * @property \Illuminate\Database\Eloquent\Collection productItems
 * @property integer account_category_id
 * @property string name_ru
 * @property string name_en
 * @property boolean status
 * @property string url
 * @property string details_ru
 * @property string details_en
 * @property string icon_class
 * @property string icon_img
 * @property string icon_img_active
 */
class Product extends Model
{

    public $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'account_category_id',
        'name_ru',
        'name_en',
        'status',
        'url',
        'icon_class',
        'icon_img',
        'icon_img_active',
        'price',
        'info',
        'info_ru',
        'order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'account_category_id' => 'integer',
        'name_ru' => 'string',
        'name_en' => 'string',
        'status' => 'boolean',
        'url' => 'string',
        'icon_class' => 'string',
        'icon_img' => 'string',
        'icon_img_active' => 'string',
        'price' => 'string',
        'info' => 'string',
        'info_ru' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ru' => 'required',
        'name_en' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function accountCategory()
    {
        return $this->belongsTo(\App\Models\AccountCategory::class, 'account_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function productItems()
    {
        return $this->hasMany(\App\Models\ProductItem::class, 'product_id');
    }
}
