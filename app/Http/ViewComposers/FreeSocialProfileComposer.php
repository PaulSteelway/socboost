<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;

use App\Models\UserSocial;

class FreeSocialProfileComposer
{

  private $profiles;
  private $types;


  public function __construct() {
    $this->types = collect([
      // 'instagram' => 'Instagram',
      'tiktok' => 'TikTok'
    ]);

    $user = Auth::user();
    if ($user) {

      $this->profiles = UserSocial::where('user_id', $user->id)
        ->orderBy('active', 'desc')
        ->orderBy('private', 'asc')
        ->orderBy('open_data', 'desc')
        ->orderBy('updated_at', 'asc')
        ->toBase()
        ->get();
    }
  }


  public function compose(View $view) {
    $view->with([
      'profiles' => $this->profiles,
      'types' => $this->types,
    ]);
  }

}
