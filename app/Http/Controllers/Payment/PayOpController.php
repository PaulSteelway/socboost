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
use App\Modules\PaymentSystems\PayOpModule;
use App\Modules\PaymentSystems\RobokassaModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RobokassaController
 * @package App\Http\Controllers\Payment
 */
class PayOpController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function topUp()
    {
        try {
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
            if ($currentLocale == 'en') {
                $currency = Currency::where('code', 'USD')->first();
            } else {
                $currency = Currency::where('code', 'RUR')->first();
            }

            $promocode_code = session('topup.promocode');
            $promocode_object = Promocode::where('code', $promocode_code)->first();
            $promocode_id = null;
            if ($promocode_object) {
                $promocode_id = $promocode_object->id;
            }

            $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id);

            $path = PayOpModule::createTopupInvoice($transaction);

            return redirect($path);

        } catch (\Exception $e) {
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function processPayment(Request $request)
    {
        try {
//            if (!in_array($request->ip(), ['52.49.204.201', '54.229.170.212', '162.158.93.64'])) {
//                \Log::info('PayOp Forbidden: ' . $request->ip() . '. ' . json_encode($request->all()));
//                return response('Forbidden', 403);
//            }
            $response = $request->all();
            \Log::info('PayOp process response: ' . $request->ip() . '. ' . json_encode($response));
            if (!empty($response['transaction']['order']['id'])) {
                /** @var Transaction $transaction */
                $transaction = Transaction::find($response['transaction']['order']['id']);

                if($transaction->status == 1) {
                    Transaction::notifyFailTransaction("PayOp", $response['transaction']['order']['id']);
                }

                if ($transaction instanceof Transaction && $transaction->status == 0) {
                    if (empty($response['transaction']['error']['code']) && empty($response['transaction']['error']['message'])) {
                        $transaction->source = $response['invoice']['id'];
                        $transaction->batch_id = $response['transaction']['id'];
                        $transaction->result = 'Completed';
                        $transaction->status = 1;
                        $transaction->save();

                        if (empty($transaction->approved)) {
                            $commission = $transaction->amount * 0.01 * $transaction->commission;
                            $transactionAmount = $transaction->amount - $commission;
                            if ($transaction->currency->code == 'USD') {
                                $transactionAmount = convertUsdToRub($transactionAmount);
                            }
                            $transactionAmount = PaymentManager::checkTransactionForPromocode($transaction, $transactionAmount);
                            $transaction->wallet->refill($transactionAmount, $transaction->source);
                            $transaction->approved = true;
                            $transaction->save();

                            UserManager::accrueReferralBonusForEnter($transaction, $transactionAmount);
                            UserManager::accrueBonusWithTopup($transaction);
                        }
                    } else {
                        $transaction->source = $response['invoice']['id'];
                        $transaction->batch_id = $response['transaction']['id'];
                        $transaction->result = $response['transaction']['error']['code'] . ': ' . $response['transaction']['error']['message'];
                        $transaction->save();
                    }
                    return response('OK', 200);
                }
            }
            return response('Transaction does not exist', 404);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
