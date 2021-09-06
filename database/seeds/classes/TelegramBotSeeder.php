<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;

/**
 * Class TelegramBotSeeder
 */
class TelegramBotSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $data = [
            /**
             * Admin bot
             */
            [
                'bot_keyword'    => 'admin_bot',
                'command'        => '/start',
                'description'    => 'command',
                'method_address' => 'admin_bot\AdminBotBotStartController',
                'created_at'     => now(),
            ],

            /**
             * Account bot
             */
            [
                'bot_keyword'    => 'account_bot',
                'command'        => '/start',
                'description'    => 'command',
                'method_address' => 'account_bot\AccountBotStartController',
                'created_at'     => now(),
            ],

            /**
             * Notification bot
             */
            [
                'bot_keyword'    => 'notification_bot',
                'command'        => '/start',
                'description'    => 'command',
                'method_address' => 'notification_bot\NotificationBotStartController',
                'created_at'     => now(),
            ],
            [
                'bot_keyword'    => 'notification_bot',
                'command'        => '/auth',
                'description'    => 'command',
                'method_address' => 'notification_bot\NotificationBotAuthController',
                'created_at'     => now(),
            ],
            [
                'bot_keyword'    => 'notification_bot',
                'command'        => '/enter_password',
                'description'    => 'command',
                'method_address' => 'notification_bot\NotificationBotEnterPasswordController',
                'created_at'     => now(),
            ],
        ];

        foreach ($data as $row) {
            $checkExists = \App\Models\Telegram\TelegramBotScopes::where('bot_keyword', $row['bot_keyword'])
                ->where('command', $row['command'])
                ->where('method_address', $row['method_address'])
                ->count();

            if ($checkExists > 0) {
                echo "Telegram command '".$row['command']."' already registered.\n";
                continue;
            }

            \App\Models\Telegram\TelegramBotScopes::create($row);
            echo "Telegram command '".$row['command']."' registered.\n";
        }
    }
}
