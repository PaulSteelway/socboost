<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Youtube;

use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class YoutubeVideoWatch
 * @package App\Models\Youtube
 *
 * @property string resource_url
 * @property User user_id
 */
class YoutubeVideoWatch extends Model
{
    use Uuids;

    /** @var string $table */
    protected $table = 'youtube_video_watch';

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'resource_url',
        'user_id',
    ];

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
