<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Request;
use Storage;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.settings');
    }


    public function save_avatar()
    {
        $type = request()[0]->getMimeType();

        $extension = request()[0]->getClientOriginalExtension();
        error_log($extension);
        
        if(in_array($type, ['image/jpeg', 'image/png']) && exif_imagetype(request()[0]->getRealPath()) && ($extension == 'jpg' || $extension == 'png')) {
            $res = Storage::disk('public_folder')->putFileAs('img/avatars', request()[0], request()[0]->getClientOriginalName());
            $user = \Auth::user();
            $user->avatar = $res;
            $user->save();
            return \Response::json(['status' => 'success', 'avatar' => $res]);
        }
        else {
            return \Response::json(['status' => 'error']);
        }
    }
}
