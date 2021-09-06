<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use RedirectsUsers;

    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verify(Request $request)
    {
        if (isFreePromotionSite()) {
            $this->redirectTo = route('freePromotion.task.tasklist');
        }

        if ($request->user()->isVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->route('hash') != $request->user()->getEmailVerificationHash($request->user()->email)) {
            return redirect($this->redirectPath())->with('error', __('Invalid verification link.'));
        }

        if (Carbon::parse($request->user()->email_verification_sent)->addHours(1) < Carbon::now()) {
            return redirect($this->redirectPath())->with('error', __('Verification link expired.'));
        }

        $request->user()->verifyEmail();

        return redirect($this->redirectPath())->with('success', __('Your email address has been verified successfully.'));
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function resend(Request $request)
    {
        if ($request->user()->isVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendVerificationEmail();

        return back()->with('success', __('A verification link has been sent to your email address.'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendPhoneVerificationCode(Request $request)
    {
        if ($request->user()->phone_verified_at) {
            return redirect($this->redirectPath());
        }

        try {
            $result = $request->user()->sendPhoneSmsCode();
            if ($result) {
                return back()->with('success', __('A verification code has been sent to your phone number.'));
            } else {
                return back()->with('error', __('Check your phone number and try again.'));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark the authenticated user's phone address as verified.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyPhone(Request $request)
    {
        if ($request->user()->phone_verified_at) {
            return redirect($this->redirectPath());
        }

        if (empty($request->get('code')) || $request->get('code') != $request->user()->phone_verification_code) {
            return redirect($this->redirectPath())->with('error', __('Invalid verification code.'));
        }

        if (Carbon::parse($request->user()->email_verification_sent)->addHours(8) < Carbon::now()) {
            return redirect($this->redirectPath())->with('error', __('Verification code expired.'));
        }

        $request->user()->verifyPhone();

        return back()->with('success', __('Your phone has been verified successfully.'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changePhone(Request $request)
    {
        if ($request->user()->phone_verified_at) {
            return redirect($this->redirectPath());
        }

        try {
            /** @var User $user */
            $user = $request->user();
            $user->phone = UserManager::getFormattedPhone($request->phone);
            $user->save();
            $result = $user->sendPhoneSmsCode();
            session(['phoneCodeModal' => 2]);
            if ($result) {
                return back()->with('success', __('A verification code has been sent to your phone number.'));
            } else {
                return back()->with('error', __('Check your phone number and try again.'));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
