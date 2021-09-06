<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;

use App\Repositories\SettingRepository;

class SiteStatus
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {

        $repo = new SettingRepository;
        $settings = $repo->getCacheSetting();

        if (isset($settings['site-on']) && $settings['site-on'] == 'on') {
            return $next($request);
        }

        return response()->view('customer.disabled');


        // $settings = $repo->getCacheSetting();

        // \Log::alert($request->url());
        // \Log::alert($request->ip());
        // \Log::info($request->headers);

        // return abort(503);

        // try
        //     \Log::critical($_SERVER["HTTP_CF_CONNECTING_IP"]);
        // } catch (\Exception $e) {
        // }

        //old
        // if(Setting::getValue('site-on') != 'on'){
        //     return response()->view('customer.disabled');
        // }
        // return $next($request);
    }
}
