<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramBotEvents
 * @package App\Models\Telegram
 *
 * @property integer update_id
 * @property integer message_id
 * @property integer from_id
 * @property bool from_is_bot
 * @property string|null from_first_name
 * @property string|null from_last_name
 * @property string from_username
 * @property string from_language_code
 * @property integer chat_id
 * @property string|null chat_first_name
 * @property string|null chat_last_name
 * @property string chat_username
 * @property string chat_type
 * @property integer date
 * @property string text
 * @property string bot_keyword
 * @property string bot_id
 * @property string webhook_id
 */
class TelegramBotEvents extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    protected $fillable = [
        'update_id',
        'message_id',
        'from_id',
        'from_is_bot',
        'from_first_name',
        'from_last_name',
        'from_username',
        'from_language_code',
        'chat_id',
        'chat_first_name',
        'chat_last_name',
        'chat_type',
        'chat_type',
        'date',
        'text',
        'bot_keyword',
        'bot_id',
        'webhook_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function telegram_user()
    {
        return $this->belongsTo(TelegramUsers::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(TelegramBots::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function webhook()
    {
        return $this->belongsTo(TelegramWebhooks::class, 'webhook_id');
    }
}
