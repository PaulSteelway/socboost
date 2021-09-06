<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuth
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
        $user = Auth::user();

        if (!Auth::check()) {
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }

        if ($user->tfa_token === $request->session()->get('tfa_token')) {
            return $next($request);
        }

        return redirect()->route('auth.form.token');
    }
}
