<?php

namespace App\Services;

use App\Models\UserSocial;
use App\Services\TikTokService;


class SocialService
{

  //находим и сохраняем пользователя
  public function check($type, $username, $user_id) {
    $addSocialProfile = null;

    $checkUser = $this->selectType($type, $username);

    //создаем новый профиль или обновляем старый
    if ($checkUser['success']) {
      $addSocialProfile = UserSocial::withTrashed()->updateOrCreate([
        'user_id' => $user_id,
        'social_type' => $type,
        'social_username' => $checkUser['social_username'],
      ], [
        'social_id' => $checkUser['social_id'],
        'social_avatar' => $checkUser['social_avatar'],
        'follower' => $checkUser['follower'],
        'following' => $checkUser['following'],
        'liking' => $checkUser['liking'],
        'liker' => $checkUser['liker'],
        'private' => $checkUser['private'],
        'open_data' => $checkUser['open_data'],
        'can_check' => $checkUser['can_check'],
        'deleted_at' => null,
      ]);
    }

    return [
      'success' => $checkUser['success'],
      'profile' => $addSocialProfile,
      'info' => $checkUser['info'],
    ];
  }


  //находим и отдаем пользователя
  public function getUser($type, $id, $user_id = null) {
    $q = UserSocial::where('social_type', $type);

    if ($user_id) {
      //конкретный профиль
      $q = $q
        ->where('user_id', $user_id)
        ->where('id', $id);
    } else {
      //случайный активный профиль, не равен текущему
      $q = $q
        ->where('id', '<>', $id)
        ->where('can_check', true)
        ->inRandomOrder()
        ->active();
    }

    $user = $q->first();

    if (!$user) {
      return false;
    }

    $socUserData = $this->selectType($type, $user->social_username);

    //пользователь для проверки
    //меняем данные пользователя, если у него поменялись настройки
    if ($user->can_check !== $socUserData['can_check']) {
      $user->can_check = $socUserData['can_check'];
      $user->private = $socUserData['private'];
      $user->open_data = $socUserData['open_data'];
      try {
        $user->save();
      } catch (\Exception $e) {
        \Log::alert('Не могу убрать профиль с соц. проверки, #' . $user->id);
      }
    }

    if (!$user_id && !$socUserData['can_check']) {
      \Log::alert('Пользователь #' . $user->id . ' изменил настройки');
      return $this->getUser($type, $id);
    }

    // //костыль
    // if ($user_id) {
    // }
    $socUserData['id'] = $user->id;
    return $socUserData;
  }


  public function selectType($type, $username) {

    //tiktok
    //======================
    if ($type == 'tiktok') {
      $serv = new TikTokService;
      $checkUser = $serv->checkUsername($username);
    }

    //instagram
    //======================
    if ($type == 'instagram') {
    }

    return $checkUser;
  }


  public function checkData($data, $social_username, $social_type, $mode = 'like') {
    //предельные значения, при которых не меняются данные
    $maxLike = 100500;
    $maxFollower = 100500;

    //Проверяемый акк
    $me = $this->selectType($social_type, $social_username);
    if (!$me['success'] || !$me['can_check']) {
      return false;
    }

    $checkProfile = UserSocial::select([
      'id', 'social_type', 'social_username'
    ])
      ->where('social_type', $social_type)
      ->find($data['task']['id']);

    if(!$checkProfile) {
      return false;
    }

    //Проверяемый акк
    $checker = $this->selectType($social_type, $checkProfile->social_username);
    if (!$me['success'] || !$me['can_check']) {
      return false;
    }


    if ($mode == 'like') {
      if ((int)$data['me']['liking'] < (int)$me['liking']
       && (int)$data['task']['liker'] < (int)$checker['liker']) {
        return true;
      }

      if ((int)$data['me']['liking'] > $maxLike
       && (int)$data['task']['liker'] < (int)$checker['liker']) {
        return true;
      }

      if ((int)$data['me']['liking'] < (int)$me['liking']
       && (int)$data['task']['liker'] > $maxLike) {
        return true;
      }
    }

    if ($mode == 'follower') {
      if ((int)$data['me']['following'] < (int)$me['following']
       && (int)$data['task']['follower'] < (int)$checker['follower']) {
        return true;
      }

      if ((int)$data['me']['following'] > $maxFollower
       && (int)$data['task']['follower'] < (int)$checker['follower']) {
        return true;
      }

      if ((int)$data['me']['following'] < (int)$me['following']
       && (int)$data['task']['follower'] > $maxFollower) {
        return true;
      }
    }

    return false;
  }


}
