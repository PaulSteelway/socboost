<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use App;

/**
 * Class SetLang
 * @package App\Http\Middleware
 */
class SetLang
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('lang') || !empty($_COOKIE['lang'])) {
            $locale = session()->has('lang') ? session('lang') : $_COOKIE['lang'];
            App::setLocale($locale);
            Carbon::setLocale($locale);
            return $next($request);
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = null;
        }


        $currency = geoip($ip);

        if (!isset($currency->currency) || $currency->currency === '' || $currency->country == 'not available') {
            $currency = 'RUB';
        } else {
            $currency = $currency->currency;
        }

        $ruCurrencies = [
            'UAH',
            'RUB',
            'BYR',
            'KZT',
            'PLN',
        ];

        $default = 'en';

        if (in_array($currency, $ruCurrencies)) {
            $default = 'ru';
        }

        if (isset($_COOKIE['lang']) && !session()->has('lang') || (isset($_COOKIE['lang']) && $_COOKIE['lang'] !== $default)) {
            $checkExists = App\Models\Language::where('code', $_COOKIE['lang'])->get()->count();

            if ($checkExists == 0) {
                setcookie('lang', false, time() - 3600);
            }

            $session_lang = $_COOKIE['lang'] == $default ? $_COOKIE['lang'] : $default;
            session([
                'lang' => $session_lang
            ]);
        }

        $locale = session('lang', $default);
        if (!$locale) {
            $locale = $default;
        }

        if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $locale) {
            setcookie('lang', $locale, Carbon::now()->addDays(365)->timestamp, '/');
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
