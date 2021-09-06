<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        if (empty($request->get('email'))) {
            $request->validate([
                'phone' => 'required',
                'code' => 'required',
                'password' => 'required|confirmed|min:6',
            ], $this->validationErrorMessages());

            $user = User::where('phone', $request->get('phone'))->first();
            if ($user instanceof User and $user->phone_verification_code == $request->get('code')) {
                $this->resetPassword($user, $request->get('password'));
                $user->phone_verification_code = null;
                $user->save();
                return $this->sendResetResponse($request, Password::PASSWORD_RESET);
            } else {
                return redirect()->back()->withInput(['phone' => $request->get('phone')])->withErrors('Invalid code.');
            }
        } else {
            $request->validate($this->rules(), $this->validationErrorMessages());

            $tokenData = \DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->first();
            if (!empty($tokenData) && $tokenData->created_at < Carbon::now()->addMinutes(config('auth.passwords.users.expire'))) {
                $user = User::where('email', $request->email)->first();
                if ($user instanceof User) {
                    if (Auth::check()) Auth::logout();
                    $this->resetPassword($user, $request->password);
                    return $this->sendResetResponse($request, Password::PASSWORD_RESET);
                }
            }

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $response = $this->broker()->reset(
                $this->credentials($request), function ($user, $password) {
                    $this->resetPassword($user, $password);
                }
            );
            // If the password was successfully reset, we will redirect the user back to
            // the application's home authenticated view. If there is an error we can
            // redirect them back to where they came from with their error message.
            return $response == Password::PASSWORD_RESET
                ? $this->sendResetResponse($request, $response)
                : $this->sendResetFailedResponse($request, $response);
        }
    }
}
