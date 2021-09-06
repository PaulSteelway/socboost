<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;
use App\User;
use App\Models\Wallet;

class WalletComposer
{

  private $wallet;


  public function __construct() {
    $user = Auth::user();
    if ($user) {
      $this->wallet = $user->getActiveWallet();
    }
  }


  public function compose(View $view) {
    $view->with([
      'wallet' => $this->wallet,
    ]);
  }

}
