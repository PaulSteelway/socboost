<?php

namespace App\Observers;

use App\Models\UserSocial as Model;
use App\Services\CropSocAvatarImage;

class UserSocialObserver
{


  public function __construct() {
  }

  public function Clear() {

  }

  public function creating(Model $result) {
  }
  public function created(Model $result) {
    $this->Clear();

    //CropAvatarImage and save
    if ($result->social_avatar) {
      $crop_img = new CropSocAvatarImage;
      $crop_img->saveAvatarImage($result->id, $result->social_avatar);
    }
  }

  public function updating(Model $result) {
  }
  public function updated(Model $result) {
    $this->Clear();

    //CropAvatarImage and save
    if ($result->social_avatar && $result->isDirty('social_avatar')) {
      $crop_img = new CropSocAvatarImage;
      $crop_img->saveAvatarImage($result->id, $result->social_avatar);
    }
  }


  public function deleted(Model $result) {
    $this->Clear();
  }

  // public function restoring(Model $result) {
  //   $this->Clear();
  // }
  // public function restored(Model $result) {
  //   $this->Clear();
  // }

}
