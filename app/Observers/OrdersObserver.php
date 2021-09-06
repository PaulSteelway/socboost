<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use mysql_xdevapi\Exception;

/**
 * Class OrdersObserver
 * @package App\Observers
 */
class OrdersObserver
{
    /**
     * Listen to the Order created event.
     *
     * @param Order $order
     * @return void
     * @throws
     */
    public function created(Order $order)
    {
        clearCacheByArray($this->getCacheKeys($order));
        clearCacheByTags($this->getCacheTags($order));
    }

    /**
     * Listen to the Order creating event.
     *
     * @param Order $order
     * @return void
     */
    public function creating(Order $order)
    {
        $order->createUniqueOrderId();
    }

    /**
     * @param Order $order
     * @return array
     */
    private function getCacheKeys(Order $order): array
    {
        $cacheKeys = [];

        return $cacheKeys;
    }

    /**
     * @param Order $order
     * @return array
     */
    private function getCacheTags(Order $order): array
    {
        return [];
    }

    /**
     * Listen to the Order deleting event.
     *
     * @param Order $order
     * @return void
     * @throws
     */
    public function deleted(Order $order)
    {
        clearCacheByArray($this->getCacheKeys($order));
        clearCacheByTags($this->getCacheTags($order));
    }

    /**
     * Listen to the Order updating event.
     *
     * @param Order $order
     * @return void
     * @throws
     */
    public function updated(Order $order)
    {
        clearCacheByArray($this->getCacheKeys($order));
        clearCacheByTags($this->getCacheTags($order));
    }
}