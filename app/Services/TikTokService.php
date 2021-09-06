<?php

namespace App\Services;

use Daaner\TikTok\Models\UserInfo;


class TikTokService
{

  public function checkUsername($username) {

    $tt = new UserInfo;
    $user = $tt->getUser($username);

    $checkUser = [
      'success' => false,
      'info' => '',
      'social_id' => null,
      'social_username' => null,
      'social_avatar' => null,
      'following' => 0,
      'liking' => 0,
      'liker' => 0,
      'private' => true,
      'open_favorite' => false,
      'can_check' => false,
      'items' => 0, //videoCount
    ];

    if (!$user['success']) {
      $checkUser['info'] = $user['info'];
    }

    if (isset($user['result']['statusCode']) && $user['result']['statusCode'] !== 0) {
      $checkUser['info'] = __('tiktok::tiktok.user_no_found');
    }

    if ($user['success'] && isset($user['result']['userInfo']['user']['id'])) {
      $data = $user['result']['userInfo'];
      $checkUser['success'] = true;

      $checkUser['social_id'] = $data['user']['id'];
      $checkUser['social_username'] = $data['user']['uniqueId'];
      $checkUser['follower'] = $data['stats']['followerCount'];
      $checkUser['following'] = $data['stats']['followingCount'];
      $checkUser['liker'] = $data['stats']['heartCount'];
      $checkUser['liking'] = $data['stats']['diggCount'];
      $checkUser['social_avatar'] = $data['user']['avatarThumb'];
      $checkUser['private'] = $data['user']['privateAccount'];
      $checkUser['open_data'] = $data['user']['openFavorite'];

      //кол-во видео
      $checkUser['items'] = $data['stats']['videoCount'];

      //есть видео, открыты лайки, аккаунт не приватный
      //можно проверять этим аккаунтом
      if ($data['stats']['videoCount'] && $data['user']['openFavorite'] && !$data['user']['privateAccount']) {
        $checkUser['can_check'] = true;
      }
    }

    return $checkUser;
  }


}
