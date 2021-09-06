<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Str;

/**
 * Class UserReferral
 * @package App\Models
 * @version February 9, 2020, 2:01 pm UTC
 *
 * @property \App\Models\User referral
 * @property \App\Models\User user
 * @property string id
 * @property string link
 * @property string referral_id
 */
class UserReferral extends Model
{

    public $table = 'user_referrals';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'id',
        'link',
        'referral_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'link' => 'string',
        'referral_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|exists:users,id',
        'link' => 'required|string|32',
        'referral_id' => 'nullable|exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function referral()
    {
        return $this->belongsTo(\App\Models\User::class, 'referral_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id');
    }

    /**
     * @param User $user
     * @param null $referralId
     */
    public static function createUserReferralLink(User $user, $referralId = null)
    {
        $exist = True;
        while ($exist) {
            $link = Str::uuid()->getHex();
            $exist = self::where('link', $link)->first();
        }

        $referralId = $referralId === $user->id ? null : $referralId;

        self::updateOrCreate(['id' => $user->id], ['link' => $link, 'referral_id' => $referralId]);
    }
}
