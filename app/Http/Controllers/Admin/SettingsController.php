<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Admin
 */
class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin/settings/index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function switchSiteStatus()
    {
        $key = 'site-on';
        Setting::setValue($key, Setting::getValue($key) == 'on' ? 'off' : 'on');
        return back()->with('success', 'Site status changed');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeMany(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::setValue($key, $value);
        }

        return back()->with('success', __('Settings updated'));
    }
}
