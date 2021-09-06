<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Packet
 * @package App\Models
 * @version November 21, 2019, 9:41 pm UTC
 *
 * @property Category category
 * @property integer id
 * @property integer category_id
 * @property string service
 * @property integer service_id
 * @property boolean is_manual
 * @property string name_ru
 * @property string name_en
 * @property integer min
 * @property integer max
 * @property number price
 * @property string link
 * @property integer step
 * @property integer step_fixed
 * @property string icon_title1
 * @property string icon_title2
 * @property string icon_title3
 * @property string icon_title4
 * @property string icon_subtitle1
 * @property string icon_subtitle2
 * @property string icon_subtitle3
 * @property string icon_subtitle4
 * @property string icon_title1_ru
 * @property string icon_title2_ru
 * @property string icon_title3_ru
 * @property string icon_title4_ru
 * @property string icon_subtitle1_ru
 * @property string icon_subtitle2_ru
 * @property string icon_subtitle3_ru
 * @property string icon_subtitle4_ru
 * @property string info_en
 * @property string info_ru
 */
class Packet extends Model
{

    public $table = 'packets';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'category_id',
        'service',
        'service_id',
        'is_manual',
        'name_ru',
        'name_en',
        'min',
        'max',
        'price',
        'link',
        'step',
        'step_fixed',
        'icon_title1',
        'icon_title2',
        'icon_title3',
        'icon_title4',
        'icon_subtitle1',
        'icon_subtitle2',
        'icon_subtitle3',
        'icon_subtitle4',
        'icon_title1_ru',
        'icon_title2_ru',
        'icon_title3_ru',
        'icon_title4_ru',
        'icon_subtitle1_ru',
        'icon_subtitle2_ru',
        'icon_subtitle3_ru',
        'icon_subtitle4_ru',
        'only_for_vip',
        'info_en',
        'info_ru',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'service' => 'string',
        'service_id' => 'integer',
        'is_manual' => 'boolean',
        'name_ru' => 'string',
        'name_en' => 'string',
        'min' => 'integer',
        'max' => 'integer',
        'price' => 'float',
        'link' => 'string',
        'step' => 'integer',
        'step_fixed' => 'integer',
        'icon_title1' => 'string',
        'icon_title2' => 'string',
        'icon_title3' => 'string',
        'icon_title4' => 'string',
        'icon_subtitle1' => 'string',
        'icon_subtitle2' => 'string',
        'icon_subtitle3' => 'string',
        'icon_subtitle4' => 'string',
        'icon_title1_ru' => 'string',
        'icon_title2_ru' => 'string',
        'icon_title3_ru' => 'string',
        'icon_title4_ru' => 'string',
        'icon_subtitle1_ru' => 'string',
        'icon_subtitle2_ru' => 'string',
        'icon_subtitle3_ru' => 'string',
        'icon_subtitle4_ru' => 'string',
        'info_en' => 'string',
        'info_ru' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required|exists:categories,id',
        'service' => 'required',
        'service_id' => 'required|integer',
        'name_ru' => 'required|string|max:191',
        'name_en' => 'required|string|max:191',
        'min' => 'required|integer|min:1',
        'max' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0.01',
        'link' => 'nullable|string|max:128',
        'step' => 'required|integer|min:1',
        'icon_title1' => 'nullable|string',
        'icon_title2' => 'nullable|string',
        'icon_title3' => 'nullable|string',
        'icon_title4' => 'nullable|string',
        'icon_subtitle1' => 'nullable|string',
        'icon_subtitle2' => 'nullable|string',
        'icon_subtitle3' => 'nullable|string',
        'icon_subtitle4' => 'nullable|string',
        'icon_title1_ru' => 'nullable|string',
        'icon_title2_ru' => 'nullable|string',
        'icon_title3_ru' => 'nullable|string',
        'icon_title4_ru' => 'nullable|string',
        'icon_subtitle1_ru' => 'nullable|string',
        'icon_subtitle2_ru' => 'nullable|string',
        'icon_subtitle3_ru' => 'nullable|string',
        'icon_subtitle4_ru' => 'nullable|string',
        'info_en' => 'nullable|string',
        'info_ru' => 'nullable|string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
