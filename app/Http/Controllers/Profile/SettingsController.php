<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Http\Requests\RequestSaveUserSettings;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Profile
 */
class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $referral = \Auth::user()->userReferral;
        $userRefLink = !empty($referral) && !empty($referral->link) ? route('referral.route', $referral->link) : null;
        $countReferrals = UserReferral::where('referral_id', \Auth::id())->count();

        return view('profile.settings')
            ->with('userRefLink', $userRefLink)
            ->with('countReferrals', $countReferrals);
    }

    /**
     * @param RequestSaveUserSettings $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(RequestSaveUserSettings $request)
    {
        $user = \Auth::user();

        /*
         * Base settings
         */
        if ($request->has('name')) {
            $user->name = htmlspecialchars(trim($request->name));
        }
        if ($request->has('lastname')) {
            $user->lastname = htmlspecialchars(trim($request->lastname));
        }

        if ($request->has('login')) {
            $user->login = $request->login;
        }

        if ($request->has('partner_id')) {
            $user->partner_id = $request->partner_id;
        }

        if ($request->has('telegram')) {
            $user->telegram = $request->telegram;
        }

        if ($request->has('skype')) {
            $user->skype = htmlspecialchars(trim($request->skype));
        }
        if ($request->has('password') && $request->get('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        if ($request->has('country')) {
            $user->country = $request->country;
        }

        $user->save();

        /*
         * Wallets
         */
        if ($request->has('wallets')) {
            foreach ($request->wallets as $walletId => $walletAddress) {
                $wallet = $user->wallets()
                    ->where('id', $walletId)
                    ->first();

                if (null == $wallet) {
                    continue;
                }

                $walletAddress = htmlspecialchars(trim($walletAddress));

                if (!empty($walletAddress) && $walletAddress != $wallet->external) {
                    $wallet->external = $walletAddress;
                    $wallet->save();
                }
            }
        }

        return redirect()->route('profile.settings')->with('success', __('Settings successfully saved!'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateReferralLink(Request $request)
    {
        UserReferral::createUserReferralLink(\Auth::user());
        return back();
    }
}
