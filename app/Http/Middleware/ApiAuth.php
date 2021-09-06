<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $request->request->add(['user_api_id' => Auth::id()]);
            return $next($request);
        }

        return response()->json(['data' => null, 'error' => 'Unauthorized'], 401);
    }
}
