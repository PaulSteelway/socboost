<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LanguagesTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            TransactionTypesSeeder::class,
            CountriesSeeder::class,
            SettingSeeder::class,
            TranslationsSeeder::class,
            TelegramBotSeeder::class,
            TaskScopesSeeder::class,
        ]);
    }
}
