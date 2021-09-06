<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewsLang
 * @package App\Models
 *
 * @property string news_id
 * @property string lang_id
 * @property integer show
 * @property string title
 * @property string teaser
 * @property string text
 * @property Carbon created_at
 */
class NewsLang extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'news_id',
        'lang_id',
        'show',
        'title',
        'teaser',
        'text',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

}
