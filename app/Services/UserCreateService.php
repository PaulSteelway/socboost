<?php

namespace App\Services;

use App\Models\User;


class UserCreateService
{

  public function createUser($data) {
    $err = 'Пользователь создан';
    $process = false;

    $user = new User;
    $user->email = $data['email'];
    $user->login = $data['login'];
    $user->password = bcrypt($data['password']);

    //не обязательные
    if (isset($data['country'])) {
      $user->country = $data['country'];
    }

    try {
      $process = $user->save();
    } catch (\Exception $e) {
      $err = 'Не удалось создать пользователя';
    }

    return [
      'success' => $process,
      'info' => $err,
      'user' => $user,
    ];
  }

}
