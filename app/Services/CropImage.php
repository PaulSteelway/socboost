<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Log;

class CropImage
{

  /**
  * @return $image_url
  */

  public function saveImage($id, $image, $dir = '/img/', $del = false, $w = 100, $h = 100, $crypt = false, $uuid = false)
  {

    $file = null;
    $status = false;
    $isHTTP = true;
    $isDeleted = false;

    if (stripos($image, 'http') === false) {
      $isHTTP = false;
    }

    if ($isHTTP) {
      try {
        $getFile = Http::timeout(10)
          ->retry(3, 100)
          ->get($image);
        if ($getFile->successful()) {
          $file = $getFile->body();
        } else {
          return false;
        }
      } catch (\Exception $e) {
        Log::warning('IMG: Cannot URL #'.$id.' read ' . $image . ' ---' . $dir);
        return false;
      }
    } else {
      if (stristr($image, '?', true)) {
        $image = stristr($image, '?', true);
      }

      try {
        $file = file_get_contents($image);
      } catch (\Exception $e) {}
    }


    if (!$file) {
      try {
        $file = file_get_contents(public_path() . $image);
      } catch (\Exception $e) {
        try {
          $file = file_get_contents(public_path() . '/' . $image);
        } catch (\Exception $e) {
          Log::warning('IMG: Cannot PUBLIC read ' . $image);
          return false;
        }
      }
    }

    $filename = $id . '.jpg';
    if ($crypt) {
      $filename = md5($id) . '.jpg';

    }

    //try convert uuid in short
    if ($uuid) {
      $folder_name = floor((int)substr($id, 1, 8) / 10000);
    } else {
      $folder_name = floor((int)$id / 1000);
    }

    $imagepath = $dir . $folder_name . '/';

    //создаем папку
    if (!is_dir(public_path() . $imagepath)) {
      try {
        mkdir(public_path() . $imagepath, 0777, true);

      } catch (\Exception $e) {
        Log::critical('IMG: Error create folder ' . $imagepath);
        $imagepath = '/img/profiles/';
      }
    }
    $image_url = $imagepath . $filename;

    //save
    try {
      $img = Image::make($file)->encode('jpg', 80);
      $img->resize($w, $h, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

      $status = $img->save(public_path() . $image_url);

    } catch (\Exception $e) {
      Log::warning('IMG: Cannot CONVERT ' . $image);
      return false;
    }

    if ($status               //сохранили
     && $del                  //нужно удалять
     && !$isHTTP              //локальный файл
    ){
      try {
        unlink($image);
        $isDeleted = true;
      } catch (\Exception $e) {
        try {
          unlink(public_path() . $image);
          $isDeleted = true;
        } catch (\Exception $e) {
          Log::warning('IMG: Error DELETE PUBLIC ' . $image);
        }
      }
    }

    return [
      'success' => (bool) $status,
      'deleted' => $isDeleted,
      'image' => $image_url
    ];
  }

}
