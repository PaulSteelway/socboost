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
use App\Modules\PaymentSystems\QIWIModule;
use App\Modules\PaymentSystems\RobokassaModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RobokassaController
 * @package App\Http\Controllers\Payment
 */
class QIWIController extends Controller
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
            $currentLocale = session('topup.current_locale');
            if (empty($paymentSystem) || $currentLocale == 'en') {
                return redirect()->route('profile.topup')->with('error', __('Can not process your request, try again.'));
            }
            $amount = abs(session('topup.amount'));
            $currency = Currency::where('code', 'RUR')->first();
            $promocode_code = session('topup.promocode');
            $promocode_object = Promocode::where('code', $promocode_code)->first();
            $promocode_id = null;
            if ($promocode_object) {
                $promocode_id = $promocode_object->id;
            }

            $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id);
            $qiwi_module = new QIWIModule();
            $path = $qiwi_module->getRedirectPathForPay($transaction);
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
            $response = $request->all();
            $header_hash = $request->header('X-Api-Signature-SHA256');
            $qiwi_module = new QIWIModule();
            if ($qiwi_module->checkSignature($header_hash, $response)) {
                /** @var Transaction $transaction */
                $transaction = Transaction::find($response['bill']['billId']);

                if($transaction->status == 1) {
                    Transaction::notifyFailTransaction("QIWI", $response['bill']['billId']);
                }

                if ($transaction instanceof Transaction && $transaction->status == 0) {
                    if(!$transaction->approved && $response['bill']['status']['value'] == 'PAID') {
                        $transaction->batch_id = $response['bill']['siteId'];
                        $transaction->result = 'Completed';
                        $transaction->status = 1;
                        $transaction->save();

                        $commission = $transaction->amount * 0.01 * $transaction->commission;
                        $transactionAmount = $transaction->amount - $commission;

                        $transactionAmount = PaymentManager::checkTransactionForPromocode($transaction, $transactionAmount);
                        $transaction->wallet->refill($transactionAmount, $transaction->source);
                        $transaction->approved = true;
                        $transaction->save();

                        UserManager::accrueReferralBonusForEnter($transaction, $transactionAmount);
                        UserManager::accrueBonusWithTopup($transaction);
                        return response('OK', 200);
                    }
                }
                return response('Transaction not found', 404);
            } else {
                return response('Signature mismatch', 404);
            }
        } catch (\Exception $e) {
            return response('Error', 500);
        }
    }

    public function successPayment(Request $request)
    {
        $success = __('Your balance has been successfully replenished');
        return redirect()->route('profile.topup')->with('success', $success);
    }
}
