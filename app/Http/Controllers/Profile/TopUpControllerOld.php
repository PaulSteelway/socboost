<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestTopup;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PaymentSystem;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Http\Request;

class TopUpControllerOld extends Controller
{

  /**
  * @param Request $request
  * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
  */
  public function index(Request $request) {

    return view('profile.topup', [
      'amount' => $request->get('amount')
    ]);
  }





  // nit:Daan need refactor



    /**
     * @param RequestTopup $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $extractCurrency = explode(':', $request->currency);
        $promocode = $request->promocode;
        if (isset($promocode)) {
            if (!Promocodes::check($promocode)) {
                session()->flash('warning', __("Invalid promocode"));
            }
        }
        $paymentSystem = PaymentSystem::where('id', $extractCurrency[0])->first();
        if (isset($request->package_id)) {
            $package = Package::find($request->package_id);
            $paymentSystem = PaymentSystem::where('code', 'unitpay')->first();
        }


        if (empty($paymentSystem)) {
            return back()->with('error', __('Undefined payment system'))->withInput();
        }

        $currency = $paymentSystem->currencies()->where('code', 'RUR')->first();

        if (empty($currency)) {
            return back()->with('error', __('Undefined currency'))->withInput();
        }

        $psMinimumTopupArray = @json_decode($paymentSystem->minimum_topup, true);
        $psMinimumTopup      = isset($psMinimumTopupArray[$currency->code])
            ? $psMinimumTopupArray[$currency->code]
            : 0;

        $amount  = !empty($package) ? $package->price : $request->amount;
        if ($amount < $psMinimumTopup) {
            return back()->with('error', __('Minimum balance recharge is ').$psMinimumTopup.$currency->symbol)->withInput();
        }

        if (!empty($package)) {
            session()->flash('topup.test_package_id', $package->id);
            session()->flash('topup.order_link', $request->link);
        }
        session()->flash('topup.amount', $amount);

        session()->flash('topup.payment_system', $paymentSystem);
        session()->flash('topup.currency', $currency);
        session()->flash('topup.promocode', $request->promocode);
        session()->flash('topup.i', $request->i);
        session()->flash('topup.current_locale', $request->current_locale);

        return redirect()->route('profile.topup.' . $paymentSystem->code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentMessage(Request $request)
    {
        if ($request->has('result') && $request->result == 'ok') {
            session()->flash('success', __('Balance successfully updated'));
        } elseif ($request->has('result') && $request->result == 'error') {
            session()->flash('error', __('Can not update your balance'));
        }

        return view('profile.topup');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function successPayment(Request $request)
    {
        return redirect()->route('profile.topup')->with('success', __('Your balance has been successfully replenished'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failPayment(Request $request)
    {
        return redirect()->route('profile.topup')->with('error', __('Failed to add funds'));
    }
}
