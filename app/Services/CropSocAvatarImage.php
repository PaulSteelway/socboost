<?php

namespace App\Services;

use App\Models\UserSocial;
use App\Services\CropImage;
use Log;

class CropSocAvatarImage
{

  public function saveAvatarImage($id, $image) {
    $path = '/img/profiles/';

    if (stripos($image, $path) === false) {
      $crop = new CropImage;
      $croped = $crop->saveImage($id, $image, $path, false, 100, 100);

      if (is_bool($croped) && $croped == false) {
        try {
          $p = UserSocial::find($id);
          $p->update(['social_avatar' => null]);
        } catch (\Exception $e) {
          Log::warning('IMG: Error SocAvatar delete ' . $id);
        }
      }

      if (is_array($croped) && isset($croped['success']) && $croped['success']) {
        try {
          $p = UserSocial::find($id);
          $p->update(['social_avatar' => $croped['image'] .'?' . time()]);
        } catch (\Exception $e) {
          Log::warning('IMG: Error SocAvatar save ' . $croped['image']);
        }
      }
    }

    return true;
  }

}
