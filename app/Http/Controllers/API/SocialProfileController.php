<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\SocialService;
use App\Models\UserSocial;

use Carbon\Carbon;

class SocialProfileController extends Controller
{

  /*
   * Проверяем профайл
   */
  public function check(Request $request) {
    if (!$request->id || !$request->type || !$request->checked_id || !$request->user_api_id) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    $profile = UserSocial::where('user_id', $request->user_api_id)
      ->whereId($request->id)
      ->where('social_type', $request->type)
      ->where('private', false)
      ->where('open_data', true)
      // ->active()
      ->first();

    if (!$profile || !isset($profile->checked) || !$profile->checked) {
      return [
        'success' => false,
        'info' => __('api2.data_checked_error'),
      ];
    }


    //ошибка на соответствие в поле или очень долго
    if ($profile->checked['me']['id'] !== (int) $request->id
      || $profile->checked['task']['id'] !== (int) $request->checked_id
      || Carbon::parse($profile->checked['date'])->addMinutes(100) < Carbon::now()) {

      \Log::info('Ошибка проверки - #'. $request->id . ' - ' . $request->checked_id);
      return [
        'success' => false,
        'info' => __('api2.data_checked_error'),
      ];
    }

    $serv = new SocialService;
    $success = $serv->checkData($profile->checked, $profile->social_username, $profile->social_type);
    $info = __('api2.data_checked_error');
    $save = false;

    if ($success) {
      $profile->active = true;
      $profile->checked = null;

      try {
        $save = $profile->save();
        $info = '';
      } catch (\Exception $e) {
        \Log::info('Ошибка сохранения подтверждения - #'. $profile->id);
      }

    }

    return [
      'success' => $save,
      'info' => $info,
    ];
  }

  /*
   * Обновляем аккаунт профайла
   */
  public function update(Request $request) {
    if (!$request->id || !$request->user_api_id) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    $profile = UserSocial::where('user_id', $request->user_api_id)
      ->where('id', $request->id)
      ->first();

    if (!$profile) {
      return [
        'success' => false,
        'info' => __('api2.error_profile'),
      ];
    }

    $serv = new SocialService;
    $user = $serv->check($profile->social_type, $profile->social_username, $request->user_api_id);

    return [
      'success' => $user['success'],
      'info' => $user['info'],
      'profile' => [
        'id' => $user['profile']['id'],
        'social_type' => $user['profile']['social_type'],
        'social_avatar' => $user['profile']['social_avatar'],
        'social_username' => $user['profile']['social_username'],
        'active' => $user['profile']['active'],
        'private' => $user['profile']['private'],
        'open_data' => $user['profile']['open_data'],
      ],
    ];



  }

  /*
   * Добавляем / восстанавливаем новый аккаунт профайла
   */
  public function add(Request $request) {
    if (!$request->type || !$request->username || !$request->user_api_id) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    //обрабатываем имя
    $username = $this->fixUsername($request->username);
    if (!$username) {
      return [
        'success' => false,
        'info' => __('api2.error_username'),
      ];
    }


    $profile = UserSocial::where('user_id', $request->user_api_id)
      ->where('social_type', $request->type)
      ->where('social_username', $username)
      ->withTrashed()
      ->first();

    $active = false;

    if ($profile && $profile->deleted_at == null) {
      return [
        'success' => false,
        'info' => __('api2.isset_profile'),
      ];
    }

    $serv = new SocialService;
    $user = $serv->check($request->type, $username, $request->user_api_id);

    return [
      'success' => $user['success'],
      'info' => $user['info'],
      'profile' => [
        'id' => $user['profile']['id'],
        'social_type' => $user['profile']['social_type'],
        'social_avatar' => $user['profile']['social_avatar'],
        'social_username' => $user['profile']['social_username'],
        'active' => $user['profile']['active'],
        'private' => $user['profile']['private'],
        'open_data' => $user['profile']['open_data'],
      ],
    ];
  }


  /*
   * Удаляем аккаунт профайла
   */
  public function delete(Request $request) {
    if (!$request->id || !$request->user_api_id) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    $profile = UserSocial::where('user_id', $request->user_api_id)
      ->where('id', $request->id)
      ->delete();

    return [
      'success' => (bool) $profile
    ];
  }


  /*
   * Список аккаунтов для проверки
   */
  public function get(Request $request) {
    if (!$request->id || !$request->user_api_id || !$request->type) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    $serv = new SocialService;
    $me = $serv->getUser($request->type, $request->id, $request->user_api_id);

    if (!$me) {
      return [
        'success' => false,
        'info' => __('api2.data_empty'),
      ];
    }

    $userForCheck = $serv->getUser($request->type, $request->id);

    if (!$userForCheck) {
      return [
        'success' => false,
        'info' => __('api2.data_list_empty'),
      ];
    }

    $data = [
      'me' => [
        'id' => $me['id'],
        'liking' => $me['liking'],
        'following' => $me['following'],
      ],
      'task' => [
        'id' => $userForCheck['id'],
        'liker' => $userForCheck['liker'],
        'follower' => $userForCheck['follower'],
      ],
      'date' => Carbon::now(),
    ];

    $saveCheck = UserSocial::whereId($me['id'])->update([
      'checked' => $data
    ]);

    $link = '';
    if ($request->type == 'tiktok') {
      $link = 'https://tiktok.com/@' . $userForCheck['social_username'];
    }

    return [
      'success' => (bool) $saveCheck,
      'checked' => [
        'id' => $userForCheck['id'],
        'social_avatar' => $userForCheck['social_avatar'],
        'social_username' => $userForCheck['social_username'],
      ],
      'link' => $link,
    ];
  }


  public function fixUsername($username) {

    //delete #
    $username = str_replace('#', '', $username);
    //delete @
    $username = str_replace('@', '', $username);

    //full url and get last block
    $path = parse_url($username, PHP_URL_PATH);
    $username = array_slice(explode('/', $path), -1)[0];

    //delete /
    $username = str_replace('/', '', $username);

    return $username;
  }


}
