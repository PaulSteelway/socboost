<?php

namespace App\Services;

use App\Models\User;
use App\Services\CropImage;
use Log;

class CropUserAvatarImage
{

  public function saveAvatarImage($id, $image) {
    $path = '/img/avatars/';

    if (stripos($image, $path) === false) {
      $crop = new CropImage;
      $croped = $crop->saveImage($id, $image, $path, true, 230, 230, true, true);


      if (is_bool($croped) && $croped == false) {
        try {
          $p = User::find($id);
          $p->update(['avatar' => null]);
          unlink(public_path() . $image);
          Log::warning($id . ' - Avatar removed');
        } catch (\Exception $e) {
          Log::warning('IMG: Error UserAvatar save and del ' . $id);
        }
      }

      if (is_array($croped) && isset($croped['success']) && $croped['success']) {
        try {
          $p = User::find($id);
          $p->update(['avatar' => $croped['image'] .'?' . time()]);
        } catch (\Exception $e) {
          Log::warning('IMG: Error UserAvatar save ' . $croped['image']);
        }
      }

    }

    return true;
  }

}
