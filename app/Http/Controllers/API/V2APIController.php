<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\TikTokService;


class V2APIController extends Controller
{

  public function index() {

    $tts = new TikTokService;
    $check = $tts->checkUsername('tiktok');



    dd($check);

    return [
      'success' => true,
    ];
  }


}
