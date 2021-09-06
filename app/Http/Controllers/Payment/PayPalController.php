<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Managers\PaymentManager;
use App\Http\Managers\UserManager;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Promocode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\PaymentSystems\PayPalModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PayPalController
 * @package App\Http\Controllers\Payment
 */
class PayPalController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function topUp()
    {
        /** @var User $user */
        $user = Auth::user();

        $wallet = $user->getActiveWallet();
        if (empty($wallet)) {
            $wallet = Wallet::setActiveWallet($user);
        }

        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = session('topup.payment_system');
        if (empty($paymentSystem)) {
            return redirect()->route('profile.topup')->with('error', __('Can not process your request, try again.'));
        }

        $amount = abs(session('topup.amount'));

        $currentLocale = session('topup.current_locale');
        if (empty($currentLocale) || $currentLocale == 'ru') {
            $currency = Currency::where('code', 'RUR')->first();
        } else {
            $currency = Currency::where('code', 'USD')->first();
        }

        $promocode_code = session('topup.promocode');
        $promocode_object = Promocode::where('code', $promocode_code)->first();
        $promocode_id = null;
        if ($promocode_object) {
            $promocode_id = $promocode_object->id;
        }

        $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id);

        try {
            $chargeObj = PayPalModule::createTopupTransaction($transaction);
        } catch (\Exception $e) {
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }

        $transaction->source = $chargeObj->result->id;

        $transaction->save();

        return redirect($chargeObj->result->links[1]->href);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function successPayment(Request $request)
    {
        try {
            if (empty($request->get('token'))) {
                \Log::info('Paypal. Token is empty');
                throw new \Exception(__('Something went wrong. Please try again'));
            }

            $captureObj = PayPalModule::captureTransaction($request->get('token'));
            if (empty($captureObj->result->id) || empty($captureObj->result->purchase_units[0]->payments->captures[0])) {
                \Log::info('Paypal. Response is invalid');
                throw new \Exception(__('Something went wrong. Please try again'));
            }

            $capture = $captureObj->result->purchase_units[0]->payments->captures[0];

            /** @var Transaction $transaction */
            $transaction = Transaction::where('id', $capture->custom_id)
                ->where('source', $captureObj->result->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if($transaction->status == 1) {
                Transaction::notifyFailTransaction("PayPal", $capture->custom_id);
            }

            if ($transaction && $transaction->status == 0 && $capture->status === 'COMPLETED') {
                if ($transaction->result != 'COMPLETED') {
                    $transaction->result = 'COMPLETED';
                    $transaction->batch_id = $capture->id;
                    $transaction->source = '';
                    $transaction->status = 1;
                    $transaction->save();

                    $commission = $transaction->amount * 0.01 * $transaction->commission;
                    $transactionAmount = $transaction->amount - $commission;
                    if ($transaction->currency->code == 'USD') {
                        $transactionAmount = convertUsdToRub($transactionAmount);
                    }
                    $transactionAmount = PaymentManager::checkTransactionForPromocode($transaction, $transactionAmount);
                    $transaction->wallet->refill($transactionAmount, $transaction->source);
                    $transaction->update(['approved' => true]);

                    UserManager::accrueReferralBonusForEnter($transaction, $transactionAmount);
                    UserManager::accrueBonusWithTopup($transaction);
                }
            }

            \Log::info('PayPal transaction is passed. End of controller. IP: ' . $request->ip());
            return redirect()->route('profile.topup')->with('success', __('Your balance has been successfully replenished'));

        } catch (\Exception $e) {
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancelPayment(Request $request)
    {
        return view('ps.coinpayments', ['status' => 'cancel']);
    }
}
