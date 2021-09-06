<?php

namespace App\Repositories;

use Config;

abstract class InitRepository
{

  protected $model;
  private $cache_enabled;
  protected $cache_time;

  abstract protected function getModelClass();

  public function __construct() {
    //длительность кеша
    $this->cache_time = (int) Config::get('app.cache_time');
    $this->cache_enabled = (bool) Config::get('app.cache_enabled');

    $this->model = app($this->getModelClass());

    // Enable/disable cache repo
    if(!$this->cache_enabled) {
      $this->cache_time = 0;
    }
  }


  protected function startConditions() {
    return clone $this->model;
  }

}
