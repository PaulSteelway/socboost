<?php

namespace App\Repositories;

use App\Models\Setting as Model;
use App\Repositories\InitRepository;

use Cache;

class SettingRepository extends InitRepository
{

  public function __construct() {
    parent::__construct();
  }

  protected function getModelClass() {
    return Model::class;
  }


  public function getSetting() {
    $columns = [
      's_key',
      's_value',
    ];

    $result = $this->startConditions()
      ->select($columns)
      ->get();

    $setting = $result->mapWithKeys(function ($item) {
      return [$item['s_key'] => $item['s_value']];
    });

    return $setting;
  }


  public function getCacheSetting() {
    $cache_name = 'settings';

    if (Cache::has($cache_name) && !empty(Cache::get($cache_name))) {
      $data_cache = Cache::get($cache_name);
    } else {
      $data_cache = Cache::remember($cache_name, $this->cache_time, function () {
        return $this->getSetting();
      });
    }

    return $data_cache;
  }

}
