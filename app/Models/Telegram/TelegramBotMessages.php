<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramBotMessages
 * @package App\Models\Telegram
 *
 * @property string sender
 * @property string receive
 * @property string bot_id
 * @property string message
 * @property string scope_id
 * @property integer scope_is_closed
 * @property string extra_data
 * @property integer message_id
 */
class TelegramBotMessages extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    protected $fillable = [
        'sender',
        'receive',
        'bot_id',
        'message',
        'scope_id',
        'scope_is_closed',
        'extra_data',
        'message_id',
    ];

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
    public function scope()
    {
        return $this->belongsTo(TelegramBotScopes::class, 'scope_id');
    }

    /**
     * @param TelegramBotEvents $event
     * @param TelegramBots $bot
     * @return boolean
     */
    public static function closeUserScopes(TelegramBotEvents $event, TelegramBots $bot)
    {
        return TelegramBotMessages::where(function($query) use ($event) {
            $query->where('sender', 'bot')
                ->where('receive', $event->chat_id);
        })
            ->where('bot_id', $bot->id)
            ->whereNotNull('scope_id')
            ->where('scope_is_closed', 0)
            ->update([
                'scope_is_closed' => 1,
            ]);
    }
}
