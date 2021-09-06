<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;
use Jenssegers\Agent\Agent;

class UserComposer
{

  private $agent;
  private $user;


  public function __construct() {
    $this->agent = new Agent();

    $this->user = collect();
    $this->user = Auth::user();
  }


  public function compose(View $view) {
    $view->with([
      'agent' => $this->agent,
      'user' => $this->user,
    ]);
  }

}
