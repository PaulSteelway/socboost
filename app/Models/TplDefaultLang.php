<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * фраза для шаблона на языке по умолчанию
 *
 * Class TplDefaultLang
 * @package App\Models
 *
 * @property string id
 * @property string text
 * @property string lang_id
 * @property string category
 */
class TplDefaultLang extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'id',
        'text',
        'lang_id',
        'category'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translate()
    {
        return $this->hasMany(TplTranslation::class, 'default_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

}
