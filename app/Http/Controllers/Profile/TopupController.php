<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RequestTopup;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PaymentSystem;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Support\Facades\Auth;


class TopupController extends Controller
{
  public function index(Request $request) {
      return view('profile.topup', [
        'amount' => $request->get('amount')
      ]);
  }

  public function successPayment(Request $request) {
    $sessionOldData = session()->get(Auth::id() . '_order_unfinished');

    if(!empty($sessionOldData) && isset($sessionOldData['category_url'])) {
      return redirect(route('order.category', [$sessionOldData['category_url'], 'autoSubmitOrder' => 1]));
    }
    else {
      return redirect()->route('profile.topup')->with('success', __('Your balance has been successfully replenished'));
    }
  }

  public function failPayment(Request $request) {
    $sessionOldData = session()->get(Auth::id() . '_order_unfinished');

    if(!empty($sessionOldData) && isset($sessionOldData['category_url'])) {
      return redirect(route('order.category', $sessionOldData['category_url']));
    }
    else {
      return redirect()->route('profile.topup', [
        'error' => __('Failed to add funds')
      ]);
    }
  }

  // nit:Daan need refactor


  public function paymentMessage(Request $request) {

    if ($request->has('result') && $request->result == 'ok') {
      session()->flash('success', __('Balance successfully updated'));
    } elseif ($request->has('result') && $request->result == 'error') {
      session()->flash('error', __('Can not update your balance'));
    }

    return view('profile.topup');
  }




  public function sendPost(Request $request) {

    session([Auth::id() . '_order_unfinished' => null]);

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

      if(app()->getLocale() == 'en') {
        $paymentSystem = PaymentSystem::where('code', 'unitpay')->first();
      }
      else {
        $paymentSystem = PaymentSystem::where('code', 'stripe')->first();
      }
    }


    if (empty($paymentSystem)) {
      return back()->with('error', __('Undefined payment system'))->withInput();
    }

    if(app()->getLocale() == 'en') {
      if($paymentSystem->name != 'Coinbase') {
        $currency = $paymentSystem->currencies()->where('code', 'USD')->first();
      }
      else {
        $currency = $paymentSystem->currencies()->where('code', 'RUR')->first();
      }
    }
    else {
      $currency = $paymentSystem->currencies()->where('code', 'RUR')->first();
    }

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

    session(['topup.payment_system' => $paymentSystem]);
    // session()->flash('topup.payment_system', $paymentSystem);
    session()->flash('topup.currency', $currency);
    session()->flash('topup.promocode', $request->promocode);
    session()->flash('topup.i', $request->i);
    session()->flash('topup.current_locale', $request->current_locale);

    return redirect()->route('profile.topup.' . $paymentSystem->code);
  }



}
