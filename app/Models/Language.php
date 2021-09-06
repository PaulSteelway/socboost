<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * @package App\Models
 *
 * @property string id
 * @property string name
 * @property string code
 * @property string original_name
 */
class Language extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'name',
        'code',
        'original_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tplDefault()
    {
        return $this->hasMany(TplDefaultLang::class, 'lang_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tplTranslate()
    {
        return $this->hasMany(TplTranslation::class, 'lang_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(NewsLang::class, 'lang_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'lang_id');
    }

    /**
     * @return mixed
     */
    public static function getDefault()
    {
        return self::where('default', 1)->first();
    }
}
