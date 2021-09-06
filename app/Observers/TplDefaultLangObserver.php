<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\TplDefaultLang;

/**
 * Class TplDefaultLangObserver
 * @package App\Observers
 */
class TplDefaultLangObserver
{
    /**
     * @param TplDefaultLang $tplDefaultLang
     */
    public function deleting(TplDefaultLang $tplDefaultLang)
    {
        foreach ($tplDefaultLang->translate()->get() as $translate) {
            $translate->delete();
        }
    }
    
    /**
     * Listen to the TplDefaultLang created event.
     *
     * @param TplDefaultLang $tplDefaultLang
     * @return void
     * @throws
     */
    public function created(TplDefaultLang $tplDefaultLang)
    {
        clearCacheByArray($this->getCacheKeys($tplDefaultLang));
        clearCacheByTags($this->getCacheTags($tplDefaultLang));
    }

    /**
     * @param TplDefaultLang $tplDefaultLang
     * @return array
     */
    private function getCacheKeys(TplDefaultLang $tplDefaultLang): array
    {
        return [];
    }

    /**
     * @param TplDefaultLang $tplDefaultLang
     * @return array
     */
    private function getCacheTags(TplDefaultLang $tplDefaultLang): array
    {
        return [];
    }

    /**
     * Listen to the TplDefaultLang deleting event.
     *
     * @param TplDefaultLang $tplDefaultLang
     * @return void
     * @throws
     */
    public function deleted(TplDefaultLang $tplDefaultLang)
    {
        clearCacheByArray($this->getCacheKeys($tplDefaultLang));
        clearCacheByTags($this->getCacheTags($tplDefaultLang));
    }

    /**
     * Listen to the TplDefaultLang updating event.
     *
     * @param TplDefaultLang $tplDefaultLang
     * @return void
     * @throws
     */
    public function updated(TplDefaultLang $tplDefaultLang)
    {
        clearCacheByArray($this->getCacheKeys($tplDefaultLang));
        clearCacheByTags($this->getCacheTags($tplDefaultLang));
    }
}