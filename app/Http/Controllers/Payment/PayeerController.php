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
use App\Modules\PaymentSystems\PayeerModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PayeerController
 * @package App\Http\Controllers\Payment
 */
class PayeerController extends Controller
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

        $transaction = Transaction::enter($wallet, $amount);

        if (null === $transaction) {
            return redirect()->route('profile.topup')->with('error', __('Enter transaction not found.'));
        }

        $transaction->source = preg_replace('/[^0-9]/', '', $transaction->id);
        $transaction->save();

        $merchantId   = config('money.payeer_merchant_id');
        $orderId      = $transaction->source;
        $amount       = number_format($amount, 2, '.', '');
        $currencyCode = $currency->code;
        $memo         = config('money.payeer_memo');

        // Forming an array for signature generation
        $arHash = [
            $merchantId,
            $orderId,
            $amount,
            $currencyCode,
            base64_encode($memo),
        ];

        $arHash[]  = config('money.payeer_merchant_key');
        $signature = strtoupper(hash('sha256', implode(":", $arHash)));

        return view('ps.payeer', [
            'currency'   => $currencyCode,
            'amount'     => $amount,
            'user'       => $user,
            'wallet'     => $wallet,
            'merchantId' => $merchantId,
            'comment'    => $memo,
            'paymentId'  => $orderId,
            'signature'  => $signature,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function status(Request $request)
    {
        if (!$request->has('m_operation_id')
            || !$request->has('m_operation_ps')
            || !$request->has('m_operation_date')
            || !$request->has('m_operation_pay_date')
            || !$request->has('m_shop')
            || !$request->has('m_orderid')
            || !$request->has('m_curr')
            || !$request->has('m_desc')
            || !$request->has('m_status')
            || !$request->has('m_sign')) {
            \Log::info('Payeer. Strange request from: '.$request->ip().'. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

//        $ips = [
//            '185.71.65.92',
//            '185.71.65.189',
//            '149.202.17.210'
//        ];
//
//        if (!in_array($request->ip(), $ips)) {
//            \Log::info('Got request to Payeer status controller, from '.$request->ip().'. Allow requests only from: '.implode(', ', $ips));
//            return response('ok');
//        }

        $m_key = config('money.payeer_merchant_key');

        $arHash = array(
            $request->m_operation_id,
            $request->m_operation_ps,
            $request->m_operation_date,
            $request->m_operation_pay_date,
            $request->m_shop,
            $request->m_orderid,
            $request->m_amount,
            $request->m_curr,
            $request->m_desc,
            $request->m_status,
        );

        if ($request->has('m_params')) {
            $arHash[] = $request->m_params;
        }

        $arHash[]  = $m_key;
        $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

        $paymentSystem = PaymentSystem::where('code', 'payeer')->first();
        $currency      = Currency::where('code', strtoupper($request->m_curr))->first();

        if (null == $currency) {
            \Log::info('Payeer. Strange request from: '.$request->ip().'. Currency not found. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $transaction = Transaction::where('source', strtolower($request->m_orderid))
            ->where('currency_id', $currency->id)
            ->where('payment_system_id', $paymentSystem->id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if($transaction->status == 1) {
            Transaction::notifyFailTransaction("Payeer", $request->m_orderid);
        }

        if ($transaction->result != 'success' && $request->m_sign == $sign_hash && $request->m_status == 'success' && $transaction->status == 0) {
            $transaction->batch_id = $request->m_orderid;
            $transaction->result = 'success';
            $transaction->source = '';
            $transaction->status = 1;
            $transaction->save();
            $commission = $transaction->amount * 0.01 * $transaction->commission;

            $transaction->wallet->refill(($transaction->amount-$commission), $transaction->source);
            $transaction->update(['approved' => true]);
            PayeerModule::getBalances(); // обновляем баланс нашего внешнего кошелька в БД
            return response('ok');
        }

        \Log::info('Payeer hash is not passed. IP: '.$request->ip());
        return response('ok');
    }
}
