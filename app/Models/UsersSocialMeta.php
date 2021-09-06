<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersSocialMeta
 * @package App\Models
 *
 * @property string s_key
 * @property string s_value
 * @property User user_id
 */
class UsersSocialMeta extends Model
{
    use Uuids;

    /** @var string $table */
    public $table = 'users_social_meta';

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        's_key',
        's_value',
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

    /**
     * @param User $user
     * @param string $key
     * @param null $default
     * @return null
     */
    public static function getValue(User $user, string $key, $default=null)
    {
        $row = self::where('s_key', $key)
            ->where('user_id', $user->id)
            ->first();

        if (null === $row) {
            return $default;
        }

        return $row->s_value;
    }

    /**
     * @param User $user
     * @param string $key
     * @param string|null $value
     * @return string
     */
    public static function setValue(User $user, string $key, string $value = null): string
    {
        $value = $value ?? '';
        $setting = self::updateOrCreate([
            's_key'   => $key,
            'user_id' => $user->id,
        ], [
            's_value' => $value
        ]);
        return $setting ? $setting->s_value : '';
    }
}
