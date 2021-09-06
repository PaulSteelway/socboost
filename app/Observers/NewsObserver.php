<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\News;
use App\Models\NewsLang;

/**
 * Class NewsObserver
 * @package App\Observers
 */
class NewsObserver
{
    /**
     * @param News $news
     */
    public function deleting(News $news)
    {
        foreach ($news->child()->get() as $child) {
            $child->delete();
        }
    }

    /**
     * @param News $news
     * @return array
     */
    private function getCacheKeys(News $news): array
    {
        return [
            'i.allNews'
        ];
    }

    /**
     * @param News $news
     * @return array
     */
    private function getCacheTags(News $news): array
    {
        return [];
    }

    /**
     * Listen to the News created event.
     *
     * @param News $news
     * @return void
     * @throws
     */
    public function created(News $news)
    {
        clearCacheByArray($this->getCacheKeys($news));
        clearCacheByTags($this->getCacheTags($news));
    }

    /**
     * Listen to the News deleting event.
     *
     * @param News $news
     * @return void
     * @throws
     */
    public function deleted(News $news)
    {
        clearCacheByArray($this->getCacheKeys($news));
        clearCacheByTags($this->getCacheTags($news));
    }

    /**
     * Listen to the News updating event.
     *
     * @param News $news
     * @return void
     * @throws
     */
    public function updated(News $news)
    {
        clearCacheByArray($this->getCacheKeys($news));
        clearCacheByTags($this->getCacheTags($news));
    }
}