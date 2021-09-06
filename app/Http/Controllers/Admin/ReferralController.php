<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestStoreReferral;
use App\Http\Requests\RequestUpdateRefferal;
use App\Models\Referral;
use App\Http\Controllers\Controller;

/**
 * Class ReferralController
 * @package App\Http\Controllers\Admin
 */
class ReferralController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.referrals.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.referrals.create');
    }

    /**
     * @param RequestStoreReferral $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(RequestStoreReferral $request)
    {
        $referral = Referral::create($request->except([
            'on_load',
            'on_profit',
            'on_task',
        ]));

        if (!$referral) {
            return back()->with('error', __('Unable to create referral level'))->withInput();
        }

        $referral->on_load = !empty($request->on_load) ? 1 : 0;
        $referral->on_profit = !empty($request->on_profit) ? 1 : 0;
        $referral->on_task = !empty($request->on_task) ? 1 : 0;
        $referral->save();

        return redirect()->route('admin.referral.index')->with('success', __('Referral level has been created'));
    }

    /**
     * @param Referral $referral
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Referral $referral)
    {
        return view('admin.referrals.edit', [
            'referral' => $referral
        ]);
    }

    /**
     * @param RequestUpdateRefferal $request
     * @param Referral $referral
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RequestUpdateRefferal $request, Referral $referral)
    {
        $referral->update($request->except([
            'on_load',
            'on_profit',
            'on_task',
        ]));

        if (!$referral) {
            return back()->with('error', __('Unable to update referral level'))->withInput();
        }

        $referral->on_load = !empty($request->on_load) ? 1 : 0;
        $referral->on_profit = !empty($request->on_profit) ? 1 : 0;
        $referral->on_task = !empty($request->on_task) ? 1 : 0;
        $referral->save();

        return redirect()->route('admin.referral.edit', $referral->id)->with('success', __('Referral level has been updated'));
    }

    /**
     * @param $referral
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($referral)
    {
        $referral = Referral::find($referral);

        if ($referral->delete()) {
            return redirect()->route('admin.referral.index')->with('success', __('Referral level has been deleted'));
        }

        return redirect()->route('admin.referral.index')->with('error', __('Unable to delete referral level'));
    }
}
