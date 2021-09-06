<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Reviews;

/**
 * Class ReviewsObserver
 * @package App\Observers
 */
class ReviewsObserver
{
    /**
     * @param Reviews $review
     * @return array
     */
    private function getCacheKeys(Reviews $review): array
    {
        return [
            'i.customerReviews'
        ];
    }

    /**
     * @param Reviews $reviews
     * @return array
     */
    private function getCacheTags(Reviews $reviews): array
    {
        return [];
    }

    /**
     * Listen to the Reviews created event.
     *
     * @param Reviews $review
     * @return void
     * @throws
     */
    public function created(Reviews $review)
    {
        clearCacheByArray($this->getCacheKeys($review));
        clearCacheByTags($this->getCacheTags($review));
    }

    /**
     * Listen to the Reviews deleting event.
     *
     * @param Reviews $review
     * @return void
     * @throws
     */
    public function deleted(Reviews $review)
    {
        clearCacheByArray($this->getCacheKeys($review));
        clearCacheByTags($this->getCacheTags($review));
    }

    /**
     * Listen to the Reviews updating event.
     *
     * @param Reviews $review
     * @return void
     * @throws
     */
    public function updated(Reviews $review)
    {
        clearCacheByArray($this->getCacheKeys($review));
        clearCacheByTags($this->getCacheTags($review));
    }
}