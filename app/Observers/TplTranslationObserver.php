<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\TplTranslation;

/**
 * Class TplTranslationObserver
 * @package App\Observers
 */
class TplTranslationObserver
{
    /**
     * Listen to the TplTranslation created event.
     *
     * @param TplTranslation $tplTranslationObserver
     * @return void
     * @throws
     */
    public function created(TplTranslation $tplTranslationObserver)
    {
        clearCacheByArray($this->getCacheKeys($tplTranslationObserver));
        clearCacheByTags($this->getCacheTags($tplTranslationObserver));
    }

    /**
     * @param TplTranslation $tplTranslationObserver
     * @return array
     */
    private function getCacheKeys(TplTranslation $tplTranslationObserver): array
    {
        return [];
    }

    /**
     * @param TplTranslation $tplTranslationObserver
     * @return array
     */
    private function getCacheTags(TplTranslation $tplTranslationObserver): array
    {
        return [];
    }

    /**
     * Listen to the TplTranslation deleting event.
     *
     * @param TplTranslation $tplTranslationObserver
     * @return void
     * @throws
     */
    public function deleted(TplTranslation $tplTranslationObserver)
    {
        clearCacheByArray($this->getCacheKeys($tplTranslationObserver));
        clearCacheByTags($this->getCacheTags($tplTranslationObserver));
    }

    /**
     * Listen to the TplTranslation updating event.
     *
     * @param TplTranslation $tplTranslationObserver
     * @return void
     * @throws
     */
    public function updated(TplTranslation $tplTranslationObserver)
    {
        clearCacheByArray($this->getCacheKeys($tplTranslationObserver));
        clearCacheByTags($this->getCacheTags($tplTranslationObserver));
    }
}