<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Promocode;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Modules\PaymentSystems\CoinbaseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gabievi\Promocodes\Facades\Promocodes;


/**
 * Class CoinpaymentsController
 * @package App\Http\Controllers\Payment
 */
class CoinbaseController extends Controller
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

        $promocode_code = session('topup.promocode');
        $promocode_object = Promocode::where('code', $promocode_code)->first();
        $promocode_id = null;
        if ($promocode_object) {
            $promocode_id = $promocode_object->id;
        }

        if (empty($paymentSystem) || empty($currency)) {
            return redirect()->route('profile.topup')->with('error', __('Can not process your request, try again.'));
        }

        $amount = abs(session('topup.amount'));

        // convert $amount to BTC

        if(app()->getLocale() == 'en') {
            $amount = convertUsdToRub($amount);
        }

        $user = Auth::user();
//        $wallet = $user->wallets()->where([
//            ['currency_id', $currency->id],
//            ['payment_system_id', $paymentSystem->id],
//        ])->first();
//
//        if (empty($wallet)) {
//            $wallet = Wallet::newWallet($user, $currency, $paymentSystem);
//        }

        $wallet = $user->getActiveWallet();
        if (empty($wallet)) {
            $wallet = Wallet::setActiveWallet($user);
        }

        $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id);
        $metadata = array(
            'transaction_id' => $transaction->id,
            'source' => 'social_booster'
        );

        try {
            $chargeObj = CoinbaseModule::createTopupTransaction($transaction, $metadata);
        } catch (\Exception $e) {
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }
        $transaction->source = $chargeObj->offsetGet('code');
        $transaction->save();
        return redirect($chargeObj->offsetGet('hosted_url'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function status(Request $request)
    {
        $payload = file_get_contents('php://input');

        if (!empty($payload) && $this->validate_webhook($payload)) {
            $data = json_decode($payload, true);
            $event_data = $data['event']['data'];

            \Log::info('Webhook received event: ' . print_r($data, true));

            if (!isset($event_data['metadata']['transaction_id'])) {
                // Probably a charge not created by us.
                exit;
            }

            $event = $request->get('event');
            if ($event['type'] !== 'charge:confirmed') {
                \Log::info('Coinbase. Event not charge:success request from: ' . $request->ip() . ', ' . print_r($request->all(), true));
                return response('ok');
            }

            if (!isset($event['data']['code'])) {
                \Log::info('Coinbase. Code not exist in the event request from: ' . $request->ip() . ', ' . print_r($request->all(), true));
                return response('ok');
            }


            /** @var PaymentSystem $paymentSystem */
            $paymentSystem = PaymentSystem::where('code', 'Coinbase')->first();
            /** @var Currency $currency */
            try {
                $reqCurrency = $request->get('event')['data']['payments'][0]['value']['local']['currency'];
                if ($reqCurrency === 'RUB'){
                    $reqCurrency = 'RUR';
                }
            } catch (\Exception $e) {
                \Log::info('Coinbase. currency not exist in the event request from: ' . $request->ip() . ', ' . print_r($request->all(), true));
                return response('ok');
            }
            $currency = Currency::where('code', strtoupper($reqCurrency))->first();

            if (null == $currency) {
                \Log::info('Strange request from: ' . $request->ip() . '. Currency not found. Entire request is: ' . print_r($request->all(), true));
                return response('ok');
            }


            /** @var Transaction $transaction */
            $transaction = Transaction::where('source', $event['data']['code'])
                ->where('id', $event_data['metadata']['transaction_id'])
                ->where('currency_id', $currency->id)
                ->where('payment_system_id', $paymentSystem->id)
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->first();
            $last_update = end($event_data["timeline"]);
            $status = $last_update['status'];

            if($transaction->status == 1) {
                Transaction::notifyFailTransaction("Coinbase", $event_data['metadata']['transaction_id']);
            }

            if (isset($transaction) && $transaction->status == 0 && 'COMPLETED' === $status) {
                if ($transaction->result != 'complete') {
                    $transaction->batch_id = $event_data["id"];
                    $transaction->result = 'complete';
                    $transaction->source = '';
                    $transaction->approved = 1;
                    $transaction->status = 1;
                    $amount = $transaction->amount;
                    $commission = $transaction->amount * 0.01 * $transaction->commission;

                    if($transaction->promocode && Promocodes::check($transaction->promocode->code)){
                        $promocode = $transaction->promocode;
                        if($promocode->data['apply_from'] <= $amount){
                            $amount += $promocode->reward;
                            $promocode->is_disposable --;
                            $promocode->save();
                        }
                    }

                    $transaction->save();

                    $transaction->wallet->refill(($amount - $commission), $transaction->source);
                    $transaction->update(['approved' => true]);

                    UserManager::accrueReferralBonusForEnter($transaction, ($transaction->amount - $commission));
                    UserManager::accrueBonusWithTopup($transaction);
                }
            }

            \Log::info('Coinbase transaction is passed. End of controller. IP: ' . $request->ip());
            return response('ok');

        } else {
            \Log::info('HTTP_X_CC_WEBHOOK_SIGNATURE NOT VALID');
        }

    }

    public function cancelPayment(Request $request)
    {
        return view('ps.coinpayments', ['status' => 'cancel']);

    }

    public function successPayment(Request $request)
    {
        return view('ps.coinpayments', ['status' => 'success']);
    }


    /**
     * Check Coinbase webhook request is valid.
     * @param string $payload
     */
    public function validate_webhook($payload)
    {
        \Log::info('Checking Webhook response is valid');

        if (!isset($_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'])) {
            return false;
        }

        $sig = $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'];
        $secret = config('money.coinbase_shared_secret');

        $sig2 = hash_hmac('sha256', $payload, $secret);

        if ($sig === $sig2) {
            return true;
        }

        return false;
    }

}
