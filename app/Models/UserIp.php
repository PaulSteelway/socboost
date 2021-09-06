<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserIp
 * @package App\Models
 *
 * @property string user_id
 * @property string ip
 */
class UserIp extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'ip',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param string $user_id
     */
    public static function addIp(string $user_id)
    {
        self::updateOrCreate(
            ['user_id' => $user_id, 'ip' => $_SERVER['REMOTE_ADDR']],
            ['updated_at' => now()]
        );
    }

    /**
     * @return mixed
     */
    public static function manyOnIp()
    {
        $ips = self::select('ip', 'user_id')
            ->whereRaw('ip in (select ip from user_ips group by ip having count(*) > 1)')
            ->with('user:id,name,email')
            ->get();
        return $ips;
    }
}
