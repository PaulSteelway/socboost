<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DemoAutoAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $login    = 'demo';
        $password = 'demo';

        if (Auth::check() || config('app.env') != 'demo') {
            return $next($request);
        }

        try {
            $attempt = \Auth::attempt([
                'login'    => $login,
                'password' => $password
            ]);

            if ($attempt) {
                return redirect(route('admin'))->with('success', __('You was authorized automatically with login "' . $login . '" and password "' . $password . '".'));
            }
            return response('Error auth with login "'.$login.'".');
        } catch (\Exception $e) {
            return response('Error auto login: '.$e->getMessage());
        }
    }
}
