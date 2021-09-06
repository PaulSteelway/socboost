<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class FaqCategory
 * @package App\Models
 * @version April 10, 2020, 10:54 am UTC
 *
 * @property boolean status
 * @property integer order
 * @property string name_ru
 * @property string name_en
 * @property string icon
 */
class FaqCategory extends Model
{
    public $table = 'faq_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'status',
        'order',
        'name_ru',
        'name_en',
        'icon'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'boolean',
        'order' => 'integer',
        'name_ru' => 'string',
        'name_en' => 'string',
        'icon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
//        'status' => 'required',
        'order' => 'required',
        'name_ru' => 'required|string|max:191',
        'name_en' => 'required|string|max:191'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }
}
