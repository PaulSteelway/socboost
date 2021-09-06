<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Kamaln7\Toastr\Facades\Toastr;

class EmailVerification
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (empty(Auth::user()->email_verified_at)) {
                if (empty(session('temp.email.verification.period')) || (session('temp.email.verification.period') + 15 * 60) < time()) {
                    Toastr::warning(
                        __('Please, verify your email (via account Profile)'),
                        null,
                        [
                            'positionClass' => 'toast-bottom-right notification-custom-place',
                            'hideDuration' => 1000,
                        ]
                    );
                    session(['temp.email.verification.period' => time()]);
                }
            }
            if (!empty(session('user.bonus.daily.login'))) {
                Toastr::success(
                    __('You have received a bonus at the rate of 15₽ for daily using of the site'),
                    null,
                    [
                        'positionClass' => 'toast-bottom-right notification-custom-place',
                        'hideDuration' => 1000,
                    ]
                );
                session(['user.bonus.daily.login' => false]);
            }
            if (session()->get('phoneCodeModal')) {
                session(['phoneCodeModal' => session()->get('phoneCodeModal') + 1]);
                if (session()->get('phoneCodeModal') > 3) {
                    session()->forget('phoneCodeModal');
                }
            }
        }

        return $next($request);
    }
}