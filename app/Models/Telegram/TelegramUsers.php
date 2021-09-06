<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramUsers
 * @package App\Models\Telegram
 *
 * @property string id
 * @property string user_id
 * @property string bot_id
 * @property integer telegram_user_id
 * @property integer chat_id
 * @property string language
 * @property string username
 * @property string auth_check_login
 * @property string auth_check_name
 * @property integer blocked_user
 */
class TelegramUsers extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'bot_id',
        'telegram_user_id',
        'chat_id',
        'language',
        'username',
        'auth_check_login',
        'auth_check_name',
        'blocked_user',
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
    public function bot()
    {
        return $this->belongsTo(TelegramBots::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(TelegramBotEvents::class, 'from_id', 'telegram_user_id');
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->blocked_user != 0;
    }
}
