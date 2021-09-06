<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;

/**
 * Class TaskScopesSeeder
 */
class TaskScopesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scopes = [
            'youtube_video_like' => 'task_check:youtube:video_like',
            'youtube_video_comment' => 'task_check:youtube:video_comment',
            'youtube_video_watch' => 'task_check:youtube:video_watch',
            'youtube_channel_subscription' => 'task_check:youtube:channel_subscription',

            'vk_page_subscription' => 'task_check:vk:page_subscription',
            'vk_post_like' => 'task_check:vk:post_like',
            
            'telegram_channel_subscription' => 'task_check:telegram:channel_subscription',

            'facebook_new_friends' => 'task_check:facebook:new_friends',
            'facebook_page_like' => 'task_check:facebook:page_like',
        ];

        foreach ($scopes as $key => $checkerCommandName) {
            $searchScope = \App\Models\UserTasks\TaskScopes::where('key', $key)->count();

            if ($searchScope > 0) {
                echo "Task scope '".$key."' already registered.\n";
                continue;
            }

            \App\Models\UserTasks\TaskScopes::create([
                'key'                  => $key,
                'checker_command_name' => $checkerCommandName,
            ]);
            echo "Task scope '".$key."' registered.\n";
        }
    }
}
