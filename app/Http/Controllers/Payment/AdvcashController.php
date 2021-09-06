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
use App\Modules\PaymentSystems\AdvcashModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdvcashController
 * @package App\Http\Controllers\Payment
 */
class AdvcashController extends Controller
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
        $user = Auth::user();
        $wallet = $user->wallets()->where([
            ['currency_id', $currency->id],
            ['payment_system_id', $paymentSystem->id],
        ])->first();

        if (!$wallet) {
            $wallet = Wallet::newWallet($user, $currency, $paymentSystem);
        }

        $sciName      = config('money.advcash_sci_name');
        $accountEmail = config('money.advcash_account_email');
        $sciPassword  = config('money.advcash_sci_password');

        $transaction = Transaction::enter($wallet, $amount);

        $sign = $accountEmail . ':' . $sciName . ':' . $amount . ':' . $currency->code . ':' . $sciPassword . ':' . $transaction->id;
        $sign = hash('sha256', $sign);

        $comment = config('money.advcash_memo');

        return view('ps.advcash', [
            'currency' => $currency,
            'amount' => $amount,
            'accountEmail' => $accountEmail,
            'sciName' => $sciName,
            'user' => $user,
            'order' => $transaction,
            'commission' => $transaction->type->commission * 0.01 * $amount,
            'sign' => $sign,
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
        if (!isset($request->ac_transfer)
            || !isset($request->ac_start_date)
            || !isset($request->ac_sci_name)
            || !isset($request->ac_src_wallet)
            || !isset($request->ac_dest_wallet)
            || !isset($request->ac_order_id)
            || !isset($request->ac_amount)
            || !isset($request->ac_merchant_currency)) {
            \Log::info('Advcash. Strange request from: '.$request->ip().'. Entire request is: '.print_r($request->all(),true));
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $psip = [
            '50.7.115.5',
            '51.255.40.139',
        ];

        if (!in_array($request->ip(), $psip)) {
            \Log::info('Got request to Advcash status controller, from '.$request->ip().'. Allow requests only from: '.implode(', ', $psip));
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }

        $sciPassword = config('money.advcash_sci_password');
        $checkHash   = $request->ac_transfer
            . ':' . $request->ac_start_date
            . ':' . $request->ac_sci_name
            . ':' . $request->ac_src_wallet
            . ':' . $request->ac_dest_wallet
            . ':' . $request->ac_order_id
            . ':' . $request->ac_amount
            . ':' . $request->ac_merchant_currency
            . ':' . $sciPassword;
        $checkHash  = hash('sha256', $checkHash);

        if ($checkHash == $request->ac_hash) {
            $paymentSystem = PaymentSystem::where('code', 'advcash')->first();
            $currency      = Currency::where('code', strtoupper($request->ac_merchant_currency))->first();

            if (null == $currency) {
                \Log::info('Advcash. Strange request from: '.$request->ip().'. Currency not found. Entire request is: '.print_r($request->all(),true));
                return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
            }

            $transaction = Transaction::where('id', $request->ac_order_id)
                ->where('currency_id', $currency->id)
                ->where('payment_system_id', $paymentSystem->id)
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->first();

            if($transaction->status == 1) {
                Transaction::notifyFailTransaction("Advcash", $request->ac_order_id);
            }

            if ($transaction->result != $request->ac_transaction_status && $transaction->status == 0) {
                $transaction->batch_id = $request->ac_transfer;
                $transaction->result   = $request->ac_transaction_status;
                $transaction->source   = $request->ac_src_wallet;
                $transaction->status = 1;
                $transaction->save();

                if ($request->ac_transaction_status == 'COMPLETED') {
                    $commission = $transaction->amount * 0.01 * $transaction->commission;
                    $transaction->wallet->refill(($transaction->amount - $commission), $transaction->source);
                    $transaction->update(['approved' => true]);
                    $transaction->wallet->update(['external' => $request->ac_src_wallet]); // записываем/обновляем внешний ношелек
                    AdvcashModule::getBalances(); // обновляем баланс нашего внешнего кошелька в БД
                    return redirect(route('profile.topup.payment_message', ['result' => 'success']), 200);
                }
            }
            \Log::info('Advcash. Wrong transaction status, secured from double earning: IP: '.$request->ip());
            return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
        }
        \Log::info('Hash error while trying check Advcash status. IP: '.$request->ip());
        return redirect(route('profile.topup.payment_message', ['result' => 'error']), 400);
    }
}
