<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramWebhooks
 * @package App\Models
 *
 * @property string id
 * @property string telegram_bot_id
 * @property string url
 * @property string certificate
 * @property integer max_connections
 * @property string allowed_updates
 */
class TelegramWebhooks extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    protected $fillable = [
        'telegram_bot_id',
        'url',
        'certificate',
        'max_connections',
        'allowed_updates'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(TelegramBots::class, 'telegram_bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webhook_info()
    {
        return $this->hasMany(TelegramWebhooksInfo::class, 'telegram_webhook_id');
    }
}