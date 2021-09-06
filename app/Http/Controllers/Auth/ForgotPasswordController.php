<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Kamaln7\Toastr\Facades\Toastr;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sendResetLinkEmail(Request $request)
    {
        if (empty($request->get('email')) || !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            if (Auth::check()) {
                $user = Auth::user();
            } else {
                $phone = UserManager::getFormattedPhone($request->get('email'));
                $user = User::where('phone', $phone)->first();
            }
            if ($user instanceof User) {
                try {
                    $result = $user->sendPhoneSmsCode();
                    if ($result) {
                        if (Auth::check()) Auth::logout();
                        return redirect()->route('password.reset', $user->phone);
                    }
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage());
                }
            }
            return back()->withInput($request->only('email'))->withErrors(__('Invalid phone'));
        } else {
            $this->validateEmail($request);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink($request->only('email'));

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        }
    }

    protected function sendResetLinkResponse($request, $response)
    {
        Toastr::success(__('Message has been sent'), null, [
                'positionClass' => 'toast-bottom-right notification-custom-place',
                'hideDuration' => 1000,
            ]
        );
        return back()->with('status', 200)->with('msg', __('Message has been sent'));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        Toastr::error(__('Email not send to your email'), null, [
                'positionClass' => 'toast-bottom-right notification-custom-place',
                'hideDuration' => 1000,
            ]
        );
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
