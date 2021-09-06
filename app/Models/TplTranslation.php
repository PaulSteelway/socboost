<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Events\TranslationPublishedEvent;

/**
 * переводы фраз для шаблонов
 *
 * Class TplTranslation
 * @package App\Models
 *
 * @property string text
 * @property string lang_id
 * @property string default_id
 */
class TplTranslation extends Model
{
    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'text',
        'lang_id',
        'default_id',
    ];

    /**
     * когда добавляется/изменяется перевод перепубликуем языковые файлы ларавел
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::saved(function ($instance) {
            event(new TranslationPublishedEvent($instance));
        });
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate();
        });

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tplDefaultLang()
    {
        return $this->belongsTo(TplDefaultLang::class, 'default_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
