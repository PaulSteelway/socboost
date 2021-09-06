<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\Telegram;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramBots
 * @package App\Models
 *
 * @property string id
 * @property string token
 * @property string bot_id
 * @property integer is_bot
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string language_code
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string keyword
 * @property integer disabled
 */
class TelegramBots extends Model
{
    use Uuids;

    /** @var array $timestamps */
    public $timestamps = ['created_at', 'updated_at'];

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'token',
        'bot_id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'keyword',
        'disabled',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webhooks()
    {
        return $this->hasMany(TelegramWebhooks::class, 'telegram_bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(TelegramUsers::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(TelegramBotMessages::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(TelegramBotEvents::class, 'bot_id');
    }

    /**
     * @return array
     */
    public static function getExistsKeywords() : array
    {
        $directories      = \File::directories(base_path('app/Http/Controllers/Telegram/'));
        $directoriesArray = [];

        foreach ($directories as $directory) {
            $directoriesArray[] = \File::name($directory);
        }
        return $directoriesArray;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled == 1;
    }
}