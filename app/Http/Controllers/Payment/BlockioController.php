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
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\PaymentSystems\BlockioModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class BlockioController
 * @package App\Http\Controllers\Payment
 */
class BlockioController extends Controller
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

        $amount        = abs(session('topup.amount'));
        /** @var User $user */
        $user          = Auth::user();
        $wallet        = $user->wallets()->where([
            ['currency_id', $currency->id],
            ['payment_system_id', $paymentSystem->id],
        ])->first();

        if (empty($wallet)) {
            $wallet = Wallet::newWallet($user, $currency, $paymentSystem);
        }

        $transaction       = Transaction::enter($wallet, $amount);
        $sendTo = $user->getAttribute('blockio_wallet_'.strtolower($currency->code));

        if (empty($sendTo)) {
            try {
                BlockioModule::createWalletAndNotification($transaction);
                $user->refresh();

                // Reload external wallet attribute
                $sendTo = $user->getAttribute('blockio_wallet_'.strtolower($currency->code));
            } catch (\Exception $e) {
                return redirect()->route('profile.topup')->with('error', $e->getMessage());
            }
        } else {
            /*
             * Unarchive user address
             */
            try {
                BlockioModule::unarchiveAddress($currency->code, $sendTo);
            } catch (\Exception $e) {
                return redirect()->route('profile.topup')->with('error', $e->getMessage());
            }
        }

        return view('ps.blockio', [
            'currency'      => $currency,
            'user'          => $user,
            'wallet'        => $wallet,
            'transaction'   => $transaction,
            'paymentSystem' => $paymentSystem,
            'sendTo'        => $sendTo,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Exception
     */
    public function status(Request $request)
    {
        /*
         * While pinging
         */
        if ($request->has('type') && $request->type == 'ping') {
            return response('ok', 200);
        }

        /*
         * Handle enter transaction
         */
        if (!$request->has('notification_id')
                || !$request->has('delivery_attempt')
                || !$request->has('created_at')
                || !$request->has('type')
                || !$request->has('data')) {
            \Log::info('Blockio. Strange request from: '.$request->ip().'. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $data = $request->data;

        if (!isset($data['network'])
            || !isset($data['address'])
            || !isset($data['balance_change'])
            || !isset($data['amount_sent'])
            || !isset($data['amount_received'])
            || !isset($data['txid'])
            || !isset($data['confirmations'])
            || !isset($data['is_green'])) {
            \Log::info('Blockio. Strange data from: '.$request->ip().'. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $ips = [
            '45.56.79.5',
            '45.33.17.243',
            '45.56.123.170',
            '45.33.20.161',
            '96.126.125.4',
            '45.33.4.167',
            '2600:3c00::f03c:91ff:fe33:d082',
            '2600:3c00::f03c:91ff:fe52:ed5c',
            '2600:3c00::f03c:91ff:fe33:2e14',
            '2600:3c00::f03c:91ff:fe89:bb9b',
        ];

        if (!in_array($request->ip(), $ips)) {
            \Log::info('Got request to Blockio status controller, from '.$request->ip().'. Allow requests only from: '.implode(', ', $ips));
            return response('ok');
        }

        if ($request->type != 'address') {
            \Log::info('Got request to Blockio status controller, from '.$request->ip().'. Allow only ADDRESS notifications. '.print_r($request->all(),true));
            return response('ok');
        }

        $currencyCode  = strtoupper($data['network']);

        if (app('env') != 'production') {
            // testing mode
            $currencyCode = $currencyCode == 'LTCTEST' ? 'LTC' : $currencyCode;
            $currencyCode = $currencyCode == 'BTCTEST' ? 'BTC' : $currencyCode;
            $currencyCode = $currencyCode == 'DOGETEST' ? 'DOGE' : $currencyCode;
        }

        $paymentSystem = PaymentSystem::where('code', 'blockio')->first();
        $currency      = Currency::where('code', $currencyCode)->first();

        if (null == $currency) {
            \Log::info('Blockio. Strange request from: '.$request->ip().'. Currency not found. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $walletInputCode = 'blockio_wallet_'.strtolower($currency->code);
        $user            = User::where($walletInputCode, $data['address'])->first();

        if (null == $user) {
            \Log::info('Blockio. Strange request from: '.$request->ip().'. User with this wallet not found. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $amount = currencyPrecision($currency->id, $data['amount_received']);

        if ($data['confirmations'] < 2) {
            \Log::info('Blockio. Can not confirm transaction for '.$amount.'. Not enough confirmations ('.$data['confirmations'].').');
            return response('ok');
        }

        $notificationCount = \DB::table('blockio_notifications')
            ->where('user_id', $user->id)
            ->where('network', $currency->code)
            ->where('notification_id', $request->notification_id)
            ->count();

        if (0 == $notificationCount) {
            \Log::info('Blockio. Strange request from: '.$request->ip().'. Notification is not found in our DB. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        $type        = TransactionType::getByName('enter');
        $transaction = Transaction::where('currency_id', $currency->id)
            ->where('payment_system_id', $paymentSystem->id)
            ->where('type_id', $type->id)
            ->where('user_id', $user->id)
            ->where('approved', 0)
            ->whereNull('result')
            ->whereNull('batch_id')
            ->where('amount', '<=', $amount) // trick .. :)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if (null == $transaction) {
            \Log::info('Blockio. Transaction not found in our DB for '.$amount.'.');
            return response('ok');
        }

        if ($transaction->status == 1) {
            Transaction::notifyFailTransaction("Blockio", "[" . $type->id . " - " . $user->id . "]");
            \Log::info('Blockio. Transaction already completed');
            return response('ok');
        }

        $transaction->batch_id = $data['txid'];
        $transaction->result   = 'complete';
        $transaction->status = 1;
        $transaction->save();

        $commission = $transaction->amount * 0.01 * $transaction->commission;
        $transaction->wallet->refill(($transaction->amount - $commission), $transaction->source);
        $transaction->update(['approved' => true]);
        BlockioModule::getBalances(); // обновляем баланс нашего внешнего кошелька в БД
        return response('ok');
    }
}
