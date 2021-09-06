<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Faq;

/**
 * Class FaqObserver
 * @package App\Observers
 */
class FaqObserver
{
    /**
     * Listen to the Faq created event.
     *
     * @param Faq $faq
     * @return void
     * @throws
     */
    public function created(Faq $faq)
    {
        clearCacheByArray($this->getCacheKeys($faq));
        clearCacheByTags($this->getCacheTags($faq));
    }

    /**
     * @param Faq $faq
     * @return array
     */
    private function getCacheKeys(Faq $faq): array
    {
        return [
            'i.faqsList'
        ];
    }

    /**
     * @param Faq $faq
     * @return array
     */
    private function getCacheTags(Faq $faq): array
    {
        return [];
    }

    /**
     * Listen to the Faq deleting event.
     *
     * @param Faq $faq
     * @return void
     * @throws
     */
    public function deleted(Faq $faq)
    {
        clearCacheByArray($this->getCacheKeys($faq));
        clearCacheByTags($this->getCacheTags($faq));
    }

    /**
     * Listen to the Faq updating event.
     *
     * @param Faq $faq
     * @return void
     * @throws
     */
    public function updated(Faq $faq)
    {
        clearCacheByArray($this->getCacheKeys($faq));
        clearCacheByTags($this->getCacheTags($faq));
    }
}