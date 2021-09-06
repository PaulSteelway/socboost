<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSocialProfile
 * @package App\Models
 * @version August 23, 2020, 7:21 am UTC
 *
 * @property User $user
 * @property string $instagram_id
 * @property string $instagram_username
 */
class UserSocialProfile extends Model
{
    public $table = 'user_social_profiles';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'instagram_id',
        'instagram_username'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'string',
        'instagram_id' => 'string',
        'instagram_username' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
