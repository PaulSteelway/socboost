<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Models\PaymentSystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class PaymentSystemsController
 * @package App\Http\Controllers\Admin
 */
class PaymentSystemsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.payment-systems.index');
    }

    /**
     * @param PaymentSystem $paymentSystem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(PaymentSystem $paymentSystem)
    {
        $currencies = $paymentSystem->currencies;

        $paymentSystem->instant_limit    = json_decode($paymentSystem->instant_limit, true);
        $paymentSystem->minimum_topup    = json_decode($paymentSystem->minimum_topup, true);
        $paymentSystem->minimum_withdraw = json_decode($paymentSystem->minimum_withdraw, true);

        return view('admin/payment-systems/edit', [
            'ps' => $paymentSystem,
            'currencies' => $currencies
        ]);
    }

    /**
     * @param Request $request
     * @param PaymentSystem $paymentSystem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PaymentSystem $paymentSystem)
    {
        $jsonData = [
            'instant_limit'    => json_encode($request->instant_limit),
            'minimum_topup'    => json_encode($request->minimum_topup),
            'minimum_withdraw' => json_encode($request->minimum_withdraw),
        ];

        $f = $paymentSystem->update($request->except(array_keys($jsonData)));
        $s = $paymentSystem->update($jsonData);

        if ($f && $s) {
            return redirect()->route('admin.payment-systems.index')->with('success', __('Payment system has been updated'));
        }

        return back()->with('error', __('Unable to update payment system'))->withInput();
    }
}
