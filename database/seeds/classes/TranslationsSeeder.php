<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;

/**
 * Class TranslationsSeeder
 */
class TranslationsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return  void
     * @throws
     */
    public function run()
    {
        $disk = 'lang-backup';

        if (config('app.env') != 'production') {
            $this->importTranslations('demo', $disk);
        }
        $this->importTranslations('customer', $disk);
        $this->importTranslations('admin', $disk);
    }

    /**
     * @param string $category
     * @param string $disk
     * @throws Exception
     */
    private function importTranslations($category, $disk)
    {
        $files = [
            'tpl_default_langs',
            'tpl_translations',
        ];

        foreach ($files as $tableName) {
            echo "working with table '".$tableName."'.\n";

            $fileName = $tableName . '.json';

            if (!\Storage::disk($disk)->exists($fileName)) {
                \Artisan::call('publish:language_files');
                continue;
            }

            $file        = \Storage::disk($disk)->get($fileName);
            $decodedFile = json_decode($file, true);

            if (empty($decodedFile)) {
                \Artisan::call('publish:language_files');
                continue;
            }

            $data = $decodedFile[2]['data'];

            if (empty($data)) {
                throw new Exception('Check LANG-BACKUP files. Can not extract data.');
            }

            foreach ($data as $row) {
                if ($tableName == 'tpl_default_langs') {
                    if ($row['category'] != $category) {
                        continue;
                    }

                    $checkExists = DB::table($tableName)
                        ->where('lang_id', $row['lang_id'])
                        ->where('text', $row['text'])
                        ->where('category', $row['category'])
                        ->get()
                        ->count();

                    if ($checkExists == 0) {
                        $defaultLang = \App\Models\TplDefaultLang::create([
                            'text'     => $row['text'],
                            'lang_id'  => $row['lang_id'],
                            'category' => $row['category'],
                        ]);
                        $defaultLang->id = $row['id'];
                        $defaultLang->save();

                        echo "Translation '".$row['text']."' in ".$tableName." table registered.\n";
                    } else {
                        echo "Translation '".$row['text']."' in ".$tableName." table already registered.\n";
                    }
                }

                if ($tableName == 'tpl_translations') {
                    $checkExistsParent = \App\Models\TplDefaultLang::where('id', $row['default_id'])
                        ->get()
                        ->count();

                    $checkExists = DB::table($tableName)
                        ->where('default_id', $row['default_id'])
                        ->get()
                        ->count();

                    if ($checkExistsParent > 0 && $checkExists == 0) {
                        \App\Models\TplTranslation::create([
                            'text'       => $row['text'],
                            'lang_id'    => $row['lang_id'],
                            'default_id' => $row['default_id'],
                        ]);
                        echo "Translation '".$row['text']."' in ".$tableName." table registered.\n";
                    } else {
                        echo "Translation '".$row['text']."' in ".$tableName." table already registered.\n";
                    }
                }
            }
        }
    }
}
