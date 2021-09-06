<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Traits;

/**
 * Trait Filter
 * @package App\Traits
 */
trait Filter
{
    protected static function filterModel($toFilter, $sort, $sessionKey) {
        $filterQuery = [];
        $quantity = 20;
        if (session()->get($sessionKey)) {
            $filter = session()->get($sessionKey);
            $quantity = $filter['quantity'];
            $filter = array_where($filter, function ($value, $key) {
                return $key != 'quantity';
            });
            $filter = array_where($filter, function ($value, $key) {
                return $value != '0';
            });
            $filter = array_where($filter, function ($value, $key) {
                return $value != null; // in case if filer form was without some fields
            });
            for ($i = 0; $i < count($filter); $i++) {
                $filterQuery[] = [key($filter), '=', $filter[key($filter)]];
                next($filter);
            }
        }

        if ($sort and count($filterQuery)) {
            $filteredModel = $toFilter::where($filterQuery)->orderBy($sort, 'desc')->paginate($quantity);
        } elseif ($sort) {
            $filteredModel = $toFilter::orderBy($sort, 'desc')->paginate($quantity);
        } elseif (count($filterQuery)) {
            $filteredModel = $toFilter::where($filterQuery)->orderBy('created_at', 'desc')->paginate($quantity);
        } else {
            $filteredModel = $toFilter::orderBy('created_at', 'desc')->paginate($quantity);
        }

        return $filteredModel;
    }

}