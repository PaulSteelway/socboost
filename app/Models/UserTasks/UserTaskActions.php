<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Models\User;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTaskActions
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property User user_id
 * @property Tasks task_action_id
 * @property Carbon last_check_datetime
 * @property integer finished
 * @property string user_task_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class UserTaskActions extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'task_action_id',
        'last_check_datetime',
        'finished',
        'user_task_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskAction()
    {
        return $this->belongsTo(TaskActions::class, 'task_action_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTask()
    {
        return $this->belongsTo(UserTasks::class, 'user_task_id');
    }
}
