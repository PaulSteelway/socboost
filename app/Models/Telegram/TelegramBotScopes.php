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
 * Class TelegramBotScopes
 * @package App\Models\Telegram
 *
 * @property string id
 * @property string bot_keyword
 * @property string command
 * @property string description
 * @property string method_address
 * @property int hidden
 */
class TelegramBotScopes extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'bot_keyword',
        'command',
        'description',
        'method_address',
        'hidden'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(TelegramBots::class, 'bot_keyword', 'keyword');
    }
}
