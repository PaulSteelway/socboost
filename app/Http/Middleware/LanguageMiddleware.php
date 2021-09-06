<?php

namespace App\Http\Middleware;

use App;
use Carbon\Carbon;
use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if (!empty($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])) {
//            return $next($request);
//        }

//        $path = parse_url($request->url(), PHP_URL_PATH);
//        if (in_array($path, ['/unitpay/process', '/payop/process'])) {
//            return $next($request);
//        }

        $url_array = explode('.', parse_url($request->url(), PHP_URL_HOST));

        $locale = $url_array[0] == 'en' ? 'en' : 'ru';

//        if (!session()->has('lang')) {
//            $country = UserManager::determineUserCountry();
//            $localeIp = !in_array($country, config('enumerations.language_codes_ru')) ? 'en' : 'ru';
//            if ($locale != $localeIp) {
//                $route = UserManager::getLocaleRoute($localeIp);
//                return redirect($route);
//            }
//        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
