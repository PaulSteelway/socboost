<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserSocial extends Model
{

  use SoftDeletes;

  public $table = 'user_socials';

  public $fillable = [
    'user_id',
    'social_type',     //тип социальной сети
    'social_id',
    'follower',        //минимальное кол-во подписок (на пользователя подписались)
    'following',       //минимальное кол-во подписок (пользователь подписался)
    'liker',           //минимальное кол-во лайков (пользователя лайкнули)
    'liking',          //минимальное кол-во лайков (пользователь лайкнул)
    'social_avatar',
    'social_username',
    'private',         //приватный аккаунт
    'open_data',       //открыты данные
    'active',          //удален или не отключен пользователем
    'deleted_at',
    'checked',         //JSON для проверки
    'can_check',       //может использоваться для проверки лайков
  ];

  public $hidden = [
    'user_id',
  ];


  protected $casts = [
    'checked' => 'array',
    'active' => 'boolean',
    'open_data' => 'boolean',
    'can_check' => 'boolean',
    'private' => 'boolean',

    'created_at' => 'datetime:d-m-Y H:i:s',
    'updated_at' => 'datetime:d-m-Y H:i:s',
  ];


  //scopes
  public function scopeActive($query) {
    return $query->where('active', true)->where('deleted_at', null);
  }


  //relation
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }


}
