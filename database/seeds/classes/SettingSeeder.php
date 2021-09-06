<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;

/**
 * Class SettingSeeder
 */
class SettingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $websiteUrl = parse_url(config('app.url'));
        $host       = $websiteUrl['host'];

        $settings = [
            'site-on'       => 'on',
            'support-email' => 'support@' . $host,
            'phone'         => '',
            'email'         => '',
            'telegram'      => '',
            'whatsapp'      => '',
            'company_name'  => '',
            'address'       => '',
            'working_time'  => '',
            'online_chat'   => '',
        ];

        foreach ($settings as $settingKey => $settingValue) {
            $checkExists = DB::table('settings')->where('s_key', $settingKey)->count();

            if ($checkExists > 0) {
                echo "Setting '".$settingKey."' already registered.\n";
                continue;
            }

            DB::table('settings')->insert([
                's_key' => $settingKey,
                's_value' => $settingValue,
                'created_at' => now()
            ]);
            echo "Setting '".$settingKey."' registered.\n";
        }
    }
}
