<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class ImpersonateController
 * @package App\Http\Controllers\Admin
 */
class ImpersonateController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function impersonate($id)
    {
        $user = User::find($id);

        if (null == $user) {
            return back()->with('error', __('User not found'))->withInput();
        }

        Auth::user()->impersonate($user);
        return redirect(route('profile.settings'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function leave()
    {
        Auth::user()->leaveImpersonation();
        return redirect(route('admin'));
    }
}
