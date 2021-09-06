<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskActions
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property Tasks task_id
 * @property TaskScopes task_scope_id
 * @property string source_address
 */
class TaskActions extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'task_id',
        'task_scope_id',
        'source_address',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scope()
    {
        return $this->belongsTo(TaskScopes::class, 'task_scope_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTaskActions()
    {
        return $this->hasMany(UserTaskActions::class, 'task_action_id');
    }
}
