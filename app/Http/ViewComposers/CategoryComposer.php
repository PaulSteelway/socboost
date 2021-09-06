<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\CategoryTopRepository;


class CategoryComposer
{

  private $category;


  public function __construct() {
    $repo = new CategoryTopRepository;
    $this->category = $repo->getCacheTopCategory();
  }


  public function compose(View $view) {
    $view->with([
      'categories' => $this->category,
    ]);
  }

}
