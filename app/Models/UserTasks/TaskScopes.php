<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models\UserTasks;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskScopes
 * @package App\Models\UserTasks
 *
 * @property string id
 * @property string key
 * @property string checker_command_name
 */
class TaskScopes extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $timestamps */
    public $timestamps = false;

    /** @var array $fillable */
    protected $fillable = [
        'key',
        'checker_command_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany(TaskActions::class, 'task_scope_id');
    }

    /**
     * @return array|null|string
     */
    public function getScopeDescription()
    {
        switch ($this->key) {
            case 'youtube_video_like':
                return __('Youtube video link for likes');
                break;

            case 'youtube_video_comment':
                return __('Youtube video link for comments');
                break;

            case 'youtube_video_watch':
                return __('Youtube video link getting watches');
                break;

            case 'youtube_channel_subscription':
                return __('Youtube channel link for subscriptions');
                break;

            case 'instagram_page_subscription':
                return __('Intagram page URL for subscriptions');
                break;

            case 'instagram_post_like':
                return __('Instagram post URL for likes');
                break;

            case 'instagram_post_comment':
                return __('Instagram post URL for comments');
                break;

            case 'vk_page_subscription':
                return __('VK page URL for subscriptions');
                break;

            case 'vk_post_like':
                return __('VK post URL for likes');
                break;

            case 'vk_post_comment':
                return __('VK post URL for comments');
                break;

            case 'telegram_message_watch':
                return __('Telegram message ID for checking watches');
                break;

            case 'telegram_poll_activity':
                return __('Telegram poll ID for checking activity');
                break;

            case 'telegram_button_click':
                return __('Telegram button ID for checking clicks');
                break;

            case 'telegram_channel_subscription':
                return __('Telegram channel for subscriptions');
                break;

            case 'facebook_page_subscription':
                return __('Facebook page URL for subscriptions');
                break;

            case 'facebook_new_friends':
                return __('Facebook page URL for checking new friends');
                break;

            case 'facebook_page_like':
                return __('Facebook page URL for likes');
                break;

            case 'facebook_post_like':
                return __('Facebook post URL for likes');
                break;

            case 'facebook_post_comment':
                return __('Facebook post URL for comments');
                break;

            case 'odnoklassniki_page_subscription':
                return __('Odnoklassniki page URL for subscriptions');
                break;

            case 'odnoklassniki_post_like':
                return __('Odnoklassniki post URL for likes');
                break;

            case 'odnoklassniki_post_comment':
                return __('Odnoklassniki post URL for comments');
                break;

            default:
                return '';
                break;
        }
    }
}
