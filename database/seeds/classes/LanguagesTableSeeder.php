<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class LanguagesTableSeeder
 */
class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('languages')->where('id','ea7c0450-069a-11e8-ba89-0ed5f89f718b')->count() == 0) {
            DB::table('languages')->insert([
                'id'            => 'ea7c0450-069a-11e8-ba89-0ed5f89f718b',
                'name'          => 'English',
                'code'          => 'en',
                'original_name' => 'English',
                'default'       => true,
            ]);
            echo "English language registered.\n";
        } else {
            echo "English language already registered.\n";
        }

        if(DB::table('languages')->where('id','ea7c0568-069a-11e8-ba89-0ed5f89f718b')->count() == 0) {
            DB::table('languages')->insert([
                'id'            => 'ea7c0568-069a-11e8-ba89-0ed5f89f718b',
                'name'          => 'Russian',
                'code'          => 'ru',
                'original_name' => 'Русский',
                'default'       => false,
            ]);
            echo "Russian language registered.\n";
        } else {
            echo "Russian language already registered.\n";
        }
    }
}
