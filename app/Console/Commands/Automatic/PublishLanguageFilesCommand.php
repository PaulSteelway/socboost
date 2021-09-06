<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Events\TranslationPublishedEvent;
use App\Models\TplTranslation;
use Illuminate\Console\Command;

/**
 * Class PublishLanguageFilesCommand
 * @package App\Console\Commands\Automatic
 */
class PublishLanguageFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:language_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish language files from database. If files is not found.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $needUpdate = false;

        foreach (getLanguagesArray() as $lang) {
            if ($lang['code'] == 'en') {
                continue;
            }

            $f = base_path().'/resources/lang/'.$lang['code'].'.json';
            $this->comment('CHECKING FILE: '.$f);
            $this->line('');

            if (!\File::exists($f)) {
                $needUpdate = true;
            }
        }

        if (true === $needUpdate) {
            $instance = TplTranslation::first();
            event(new TranslationPublishedEvent($instance));
            $this->info('language files re-compiled');
        } else {
            $this->warn('language files don\'t need to be re-compiled.');
        }
    }
}
