<?php

namespace App\Http\Middleware;

use Closure;

class DebugbarMiddleware
{

  public function handle($request, Closure $next)
  {
    try {
      if (\Auth::user()->hasAnyRole('root')) {
        \Debugbar::enable();
      }
    } catch (\Exception $e) {}

    return $next($request);
  }
}
