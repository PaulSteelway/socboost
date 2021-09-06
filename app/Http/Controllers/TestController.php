<?php

namespace App\Http\Controllers;

use App\Models\User;

class TestController extends Controller
{

  public function t1() {

    $columns = [
      'id', 'avatar', 'blockio_wallet_btc'
    ];
    $users = User::whereNotNull('avatar')
      ->where('blockio_wallet_btc', '<>', 2)
      ->select($columns)
      ->take(10)
      ->get();
    //   ->count();
    // dd($users);

    foreach ($users as $key => $user) {
      if ($user) {
        $user->update(['blockio_wallet_btc' => 2]);
      }
    }


    dd($users);
  }
}
