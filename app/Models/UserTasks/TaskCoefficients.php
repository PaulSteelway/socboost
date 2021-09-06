<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskCoefficients
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property Tasks task_id
 * @property integer min_minutes
 * @property integer max_minutes
 * @property float reward_coefficient
 */
class TaskCoefficients extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'task_id',
        'reward_coefficient',
        'min_minutes',
        'max_minutes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}
