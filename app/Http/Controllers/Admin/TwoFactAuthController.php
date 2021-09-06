<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RequestSendToken;

class TwoFactAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authForm()
    {
        return view('auth.twofactorform');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse
     */
    public function enterToken(Request $request)
    {
        $user = Auth::user();

        if($code = $request->get('token') === $user->tfa_token) {
            $request->session()->put('tfa_token', $user->generateTfaToken());
            $request->session()->forget('attempt');

            return redirect(route('admin'));
        }

        session()->get('attempt') < 3 ?
            $request->session()->put('attempt', session()->get('attempt') + 1) :
            Auth()->logout();

        return redirect()->back()->withErrors('wrong code');
    }

    public function sendToken(RequestSendToken $request)
    {
        $user   = Auth::user();
        $number = $user->phone;
        $token  = $user->tfa_token;

        $choise = $request->input('choise');

        if('email' === $choise) {
            Mail::raw('You code: '.$token, function ($message){
                $message->to('user@user.user');
            });
            Session::put(['sended' => $choise]);
        }
        elseif ('phone' === $choise && null !== $number) {
            sendSmsTwilio($number, 'You code: '.$token);
            Session::put(['sended' => $choise]);
        }
        else {
            return redirect()->back()->withErrors('Something went wrong');
        };

        return redirect()->back();
    }

    public function statusForm()
    {
        return view('tfa.index', ['data' => Auth::user()]);
    }

    public function statusUpdate()
    {
        $user = Auth::user();

        if($user->tfa_token){

            return !$user->unsetTfaToken() ?: redirect()->route('profile');
        }

        $user->generateTfaToken();

        return redirect()->route('auth.form.token');
    }
}
