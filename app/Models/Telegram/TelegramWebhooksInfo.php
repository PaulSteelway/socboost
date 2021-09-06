<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramWebhooksInfo
 * @package App\Models\Telegram
 *
 * @property string telegram_webhook_id
 * @property string url
 * @property integer has_custom_certificate
 * @property integer pending_update_count
 * @property integer last_error_date
 * @property string last_error_message
 * @property integer max_connections
 * @property string allowed_updates
 */
class TelegramWebhooksInfo extends Model
{
    use Uuids;

    /** @var string $table */
    public $table = 'telegram_webhooks_info';

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    protected $fillable = [
        'telegram_webhook_id',
        'url',
        'has_custom_certificate',
        'pending_update_count',
        'last_error_date',
        'last_error_message',
        'max_connections',
        'allowed_updates'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function webhook()
    {
        return $this->belongsTo(TelegramWebhooks::class, 'telegram_webhook_id');
    }
}