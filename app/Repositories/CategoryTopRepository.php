<?php

namespace App\Repositories;

use App\Models\Category as Model;
use App\Repositories\InitRepository;

use Cache;

class CategoryTopRepository extends InitRepository
{

  public function __construct() {
    parent::__construct();
  }

  protected function getModelClass() {
    return Model::class;
  }


  public function getTopCategory() {

    $result = $this->startConditions()
      ->header()
      ->where('status', 1)
      ->orderBy('order', 'asc')
      ->with('subcategories')
      ->get();

    return $result;
  }


  public function getCacheTopCategory() {
    $cache_name = 'categories-top';

    if (Cache::has($cache_name) && !empty(Cache::get($cache_name))) {
      $data_cache = Cache::get($cache_name);
    } else {
      $data_cache = Cache::remember($cache_name, $this->cache_time, function () {
        return $this->getTopCategory();
      });
    }

    return $data_cache;
  }

}
