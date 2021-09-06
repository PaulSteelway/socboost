<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\User;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tasks
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property string title
 * @property string description
 * @property string link
 * @property string account_id
 * @property float reward_amount
 * @property integer execution_qty
 * @property integer executed_qty
 * @property PaymentSystem reward_payment_system_id
 * @property Currency reward_currency_id
 * @property Carbon deadline
 * @property string category
 * @property string social_category
 * @property User user_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Tasks extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var array $fillable */
    protected $fillable = [
        'title',
        'description',
        'link',
        'account_id',
        'reward_amount',
        'execution_qty',
        'executed_qty',
        'reward_payment_system_id',
        'reward_currency_id',
        'deadline',
        'comments',
        'category_id',
        'status_id',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'reward_amount' => 'integer',
        'execution_qty' => 'integer',
        'executed_qty' => 'integer',
        'link' => 'string',
        'account_id' => 'string',
        'comments' => 'array',
        'deadline' => 'date',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:categories,id'
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentSystem()
    {
        return $this->belongsTo(PaymentSystem::class, 'reward_payment_system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'reward_currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany(TaskActions::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTasks()
    {
        return $this->hasMany(UserTasks::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTaskPropositions()
    {
        return $this->hasMany(UserTaskPropositions::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coefficients()
    {
        return $this->hasMany(TaskCoefficients::class, 'task_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
