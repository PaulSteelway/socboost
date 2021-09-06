<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Modules\PaymentSystems\PerfectMoneyModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PerfectMoneyController
 * @package App\Http\Controllers\Payment
 */
class PerfectMoneyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function topUp()
    {
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = session('topup.payment_system');

        /** @var Currency $currency */
        $currency = session('topup.currency');

        if (empty($paymentSystem) || empty($currency)) {
            return redirect()->route('profile.topup')->with('error', __('Can not process your request, try again.'));
        }

        $amount = abs(session('topup.amount'));
        $user          = Auth::user();
        $wallet        = $user->wallets()->where([
            ['currency_id', $currency->id],
            ['payment_system_id', $paymentSystem->id],
        ])->first();

        if (empty($wallet)) {
            $wallet = Wallet::newWallet($user, $currency, $paymentSystem);
        }

        if ($currency->code == 'USD') {
            $payeeAccount = config('money.pm_payee_account_usd');
        } elseif ($currency->code == 'EUR') {
            $payeeAccount = config('money.pm_payee_account_eur');
        } else {
            return redirect()->route('profile.topup')->with('error', __('Wrong currency'));
        }

        $payeeName   = config('money.pm_payee_name');
        $transaction = Transaction::enter($wallet, $amount);
        $comment     = config('money.pm_memo');

        return view('ps.perfectmoney', [
            'currency' => $currency->code,
            'amount' => $amount,
            'commission' => $transaction->type->commission*0.01*$amount,
            'payeeAccount' => $payeeAccount,
            'payeeName' => $payeeName,
            'statusUrl' => route('perfectmoney.status'),
            'user' => $user,
            'wallet' => $wallet,
            'paymentId' => strtoupper($transaction->id),
            'comment' => $comment,
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Exception
     */
    public function status(Request $request)
    {
        if (!isset($request->PAYMENT_ID)
            || !isset($request->PAYEE_ACCOUNT)
            || !isset($request->PAYMENT_AMOUNT)
            || !isset($request->PAYMENT_UNITS)
            || !isset($request->PAYMENT_BATCH_NUM)
            || !isset($request->TIMESTAMPGMT)) {
            \Log::info('Perfectmoney. Strange request from: '.$request->ip().'. Entire request is: '.print_r($request->all(),true));
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $psip = ['77.109.141.170','91.205.41.208','94.242.216.60','78.41.203.75','192.168.10.1'];

        if(!in_array($request->ip(), $psip)) {
            \Log::info('Got request to Perfectmoney status controller, from '.$request->ip().'. Allow requests only from: '.implode(', ', $psip));
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $sciPassword = config('money.pm_sci_password');
        $checkHash = $request->PAYMENT_ID . ':' . $request->PAYEE_ACCOUNT . ':' . $request->PAYMENT_AMOUNT . ':' .
            $request->PAYMENT_UNITS . ':' . $request->PAYMENT_BATCH_NUM. ':' .
            $request->PAYER_ACCOUNT . ':' . strtoupper(md5($sciPassword)) . ':' . $request->TIMESTAMPGMT;
        $checkHash = strtoupper(md5($checkHash));

        if ($checkHash != $request->V2_HASH) {
            \Log::info('Perfectmoney hash is not passed. IP: '.$request->ip());
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $paymentSystem = PaymentSystem::where('code', 'perfectmoney')->first();
        $currency      = Currency::where('code', strtoupper($request->PAYMENT_UNITS))->first();

        if (null == $currency) {
            \Log::info('PerfectMoney. Strange request from: '.$request->ip().'. Currency not found. Entire request is: '.print_r($request->all(),true));
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $transaction = Transaction::where('id', strtolower($request->PAYMENT_ID))
            ->where('currency_id', $currency->id)
            ->where('payment_system_id', $paymentSystem->id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if($transaction->status == 1) {
            Transaction::notifyFailTransaction("PerfectMoney", $request->PAYMENT_ID);
        }

        if ($transaction->result != 'COMPLETED' and $request->PAYMENT_BATCH_NUM && $transaction->status == 0) {
            $transaction->batch_id = $request->PAYMENT_BATCH_NUM;
            $transaction->result = 'COMPLETED';
            $transaction->source = $request->PAYER_ACCOUNT;
            $transaction->status = 1;
            $transaction->save();
            $commission = $transaction->amount * 0.01 * $transaction->commission;

            $transaction->wallet->refill(($transaction->amount-$commission), $transaction->source);
            $transaction->update(['approved' => true]);
            $transaction->wallet->update(['external' => $request->PAYER_ACCOUNT]); // записываем/обновляем внешний ношелек
            PerfectMoneyModule::getBalances(); // обновляем баланс нашего внешнего кошелька в БД
            return redirect(route('profile.topup.payment_message', ['result' => 'success']), 200);
        }
        if (!$request->PAYMENT_BATCH_NUM) {
            \Log::info('Perfectmoney. No batch from response. IP: '.$request->ip());
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }
        \Log::info('Perfectmoney. Transaction is not passed. IP: '.$request->ip());
        return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
    }
}
