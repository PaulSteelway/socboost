<?php

namespace App\Http\Controllers;

use App\Http\Managers\UserManager;
use App\Models\Language;
use Carbon\Carbon;

/**
 * Class LanguageController
 * @package App\Http\Controllers
 */
class LanguageController extends Controller
{
    /**
     * @param $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index($locale)
    {
        $checkExists = Language::where('code', $locale)->get()->count();

        if ($checkExists == 0) {
            return back()->with('error', __('Language code error'));
        }

        $route = UserManager::getLocaleRoute($locale);

        session(['lang' => $locale]);
        setcookie('lang', $locale, Carbon::now()->addDays(365)->timestamp, '/');

        return redirect($route)->with('success', __('Site language was successfully changed'));
    }
}
