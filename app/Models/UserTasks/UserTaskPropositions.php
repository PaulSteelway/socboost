<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTaskPropositions
 * @package App\Models\UserTasks
 *
 * @property string user_id
 * @property string task_id
 */
class UserTaskPropositions extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'task_id'
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
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}
