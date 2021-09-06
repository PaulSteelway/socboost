<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\TikTokService;


class TiktokController extends Controller
{

  public function userCheck(Request $request) {
    // if (!$request->username || $request->username == '') {
    //   return [
    //     'success' => false,
    //     'info' => 'Не указано имя',
    //   ];
    // }

    $tts = new TikTokService;
    // $check = $tts->checkUsername($request->username);
    // $check = $tts->checkUsername('tiktok');
    // $check = $tts->checkUsername('neodaan');
    $check = $tts->checkUsername('matviy.sidun');
    $check = $tts->checkUsername('socialbooster.me');
    // $check = $tts->checkUsername('_tea_nestea20');



    dd($check);

    return [
      'success' => $check['success'],
      'info' => $check['info'],
    ];
  }


}
