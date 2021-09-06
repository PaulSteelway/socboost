<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Listeners;

use App\Events\TranslationPublishedEvent;
use App\Models\TplTranslation;
use Illuminate\Support\Facades\Storage;

class TranslationActionsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TranslationPublishedEvent $event
     * @return void
     */
    public function handle(TranslationPublishedEvent $event)
    {
        $filename = $event->translation->lang->code . '.json';
        $tplTranslations = TplTranslation::where('lang_id', $event->translation->lang_id)->get();
        $translations = [];

        foreach ($tplTranslations as $tplTranslation) {
            $defaultLang = $tplTranslation->tplDefaultLang()->firstOrFail()->text;
            $translation = $tplTranslation->text;

            $translations[$defaultLang] = $translation;
        }

        $translations = json_encode($translations);
        Storage::disk('lang')->put($filename, $translations);
    }
}
