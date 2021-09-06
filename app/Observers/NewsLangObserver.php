<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\NewsLang;

/**
 * Class NewsLangObserver
 * @package App\Observers
 */
class NewsLangObserver
{
    /**
     * Listen to the NewsLang created event.
     *
     * @param NewsLang $newsLang
     * @return void
     * @throws
     */
    public function created(NewsLang $newsLang)
    {
        clearCacheByArray($this->getCacheKeys($newsLang));
        clearCacheByTags($this->getCacheTags($newsLang));
    }

    /**
     * @param NewsLang $newsLang
     * @return array
     */
    private function getCacheKeys(NewsLang $newsLang): array
    {

        return [];
    }

    /**
     * @param NewsLang $newsLang
     * @return array
     */
    private function getCacheTags(NewsLang $newsLang): array
    {
        return [];
    }

    /**
     * Listen to the NewsLang deleting event.
     *
     * @param NewsLang $newsLang
     * @return void
     * @throws
     */
    public function deleted(NewsLang $newsLang)
    {
        clearCacheByArray($this->getCacheKeys($newsLang));
        clearCacheByTags($this->getCacheTags($newsLang));
    }

    /**
     * Listen to the NewsLang updating event.
     *
     * @param NewsLang $newsLang
     * @return void
     * @throws
     */
    public function updated(NewsLang $newsLang)
    {
        clearCacheByArray($this->getCacheKeys($newsLang));
        clearCacheByTags($this->getCacheTags($newsLang));
    }
}