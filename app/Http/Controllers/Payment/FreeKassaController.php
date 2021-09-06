<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Payment;

use App\Console\Commands\Automatic\ScriptCheckerCommand;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Promocode;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Modules\PaymentSystems\FreeKassaModule;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class FreeKassaController
 * @package App\Http\Controllers\Payment
 */
class FreeKassaController extends Controller
{
    /**
     * FreeKassaController constructor.
     */
    function __construct()
    {
        if (false === checkLicence())
        {
            die('licence error');
        }

        if (ScriptCheckerCommand::checkClassExists() != 'looks ok') {
            die('code corrupted');
        }

        if (LoginController::checkClassExists() != 'auth looks ok') {
            die('code corrupted');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function topUp()
    {
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = session('topup.payment_system');
        $promocode_id = null;
        /** @var Currency $currency */
        $currency = session('topup.currency');
        $promocode_code = session('topup.promocode');
        $promocode_object = Promocode::where('code', $promocode_code)->first();
        if ($promocode_object) {
            $promocode_id = $promocode_object->id;
        }

        $i = session('topup.i');

        if (empty($paymentSystem) || empty($currency)) {
            return redirect()->route('profile.topup')->with('error', __('Can not process your request, try again.'));
        }

        $amount = abs(session('topup.amount'));
        $user = Auth::user();
        $wallet = $user->wallets()->where([
            ['currency_id', $currency->id],
            ['payment_system_id', $paymentSystem->id],
        ])->first();

        if (empty($wallet)) {
            $wallet = Wallet::newWallet($user, $currency, $paymentSystem);
        }

        $transaction = Transaction::enter($wallet, $amount, $promocode_id);

        if (null === $transaction) {
            return redirect()->route('profile.topup')->with('error', __('Enter transaction not found.'));
        }

        $transaction->source = preg_replace('/[^0-9]/', '', $transaction->id);
        $transaction->save();

        $merchantId   = env('FKASSA_MERCHANT_ID', 111978);
        $orderId      = $transaction->source;
        $amount       = round($amount, 2);
        $currencyCode = $currency->code;
        $memo         = config('freekassa.memo');

        // Forming an array for signature generation
        $signature = md5($merchantId.':'.$amount.':'.config('freekassa.secret').':'.$orderId);

        return view('ps.free-kassa', [
            'currency'   => $currencyCode,
            'amount'     => $amount,
            'user'       => $user,
            'wallet'     => $wallet,
            'merchantId' => $merchantId,
            'comment'    => $memo,
            'paymentId'  => $orderId,
            'signature'  => $signature,
            'i'          => $i,
        ]);
    }

    function getIP() {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Throwable
     */
    public function status(Request $request)
    {
        $merchant_id = env('FKASSA_MERCHANT_ID', 111978);
        $merchant_secret = config('freekassa.secret');

//        if (!in_array(getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98'))) {
//            \Log::info('Not correct IP from FreeKassa');
//            die("hacking attempt!");
//        }

        $sign = md5($merchant_id.':'.$_REQUEST['AMOUNT'].':'.$merchant_secret.':'.$_REQUEST['MERCHANT_ORDER_ID']);

        if ($sign != $_REQUEST['SIGN']) {
            \Log::info('Wrong sign from FreeKassa');
            die('wrong sign');
        }

        /** @var Transaction $transaction */
        $transaction = Transaction::where('source', strtolower($_REQUEST['MERCHANT_ORDER_ID']))
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        $paymentSystem = $transaction->paymentSystem()->first();
        $currency      = $transaction->currency()->first();

        if (null == $currency) {
            \Log::info('FreeKassa. Strange request from: '.$request->ip().'. Currency not found. Entire request is: '.print_r($request->all(),true));
            return response('ok');
        }

        if($transaction->status == 1) {
            Transaction::notifyFailTransaction("FreeKassa", $_REQUEST['MERCHANT_ORDER_ID']);
        }
        
        if ($transaction->result != 'success' && $transaction->status == 0) {
            $transaction->batch_id = '';
            $transaction->result = 'success';
            $transaction->source = '';
            $transaction->status = 1;
            $transaction->save();
            $commission = $transaction->amount * 0.01 * $transaction->commission;
            $amount = $transaction->amount - $commission;
            if($transaction->promocode && Promocodes::check($transaction->promocode->code)){
                $promocode = $transaction->promocode;
                if($promocode->data['apply_from'] <= $amount){
                    $amount += $promocode->reward;
                    $promocode->is_disposable --;
                    $promocode->save();
                }
            }

            $transaction->wallet->refill($amount, $transaction->source);
            $transaction->update(['approved' => true]);
            UserManager::accrueReferralBonusForEnter($transaction, $amount);
            UserManager::accrueBonusWithTopup($transaction);
            FreekassaModule::getBalances(); // обновляем баланс нашего внешнего кошелька в БД
            return response('ok');
        }

        \Log::info('FreeKassa hash is not passed. IP: ' . $request->ip());
        return response('ok');
    }
}
