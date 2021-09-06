<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class AccountCategory
 * @package App\Models
 * @version May 19, 2020, 8:42 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection products
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
class AccountCategory extends Model
{

    public $table = 'account_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name_ru',
        'name_en',
        'status',
        'url',
        'details_ru',
        'details_en',
        'icon_class',
        'icon_img',
        'icon_img_active',
        'meta_title',
        'meta_title_ru',
        'meta_description',
        'meta_description_ru',
        'meta_keywords',
        'meta_keywords_ru',
        'title',
        'title_ru',
        'order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_ru' => 'string',
        'name_en' => 'string',
        'status' => 'boolean',
        'url' => 'string',
        'details_ru' => 'string',
        'details_en' => 'string',
        'icon_class' => 'string',
        'icon_img' => 'string',
        'icon_img_active' => 'string'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'account_category_id');
    }
}
