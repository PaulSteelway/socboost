<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reviews
 * @package App\Models
 *
 * @property string lang_id
 * @property string user_id
 * @property boolean status
 * @property integer lang_type
 * @property string name
 * @property string text
 * @property string video
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Reviews extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'lang_id',
        'user_id',
        'status',
        'lang_type',
        'name',
        'text',
        'video',
        'created_at',
        'type_id',
        'user_ip',
        'time'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
