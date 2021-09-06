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
use App\Modules\PaymentSystems\RobokassaModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RobokassaController
 * @package App\Http\Controllers\Payment
 */
class RobokassaController extends Controller
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

            $path = RobokassaModule::gerRedirectPathForPay($transaction);

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
            if (RobokassaModule::validateResponse($response['OutSum'], $response['InvId'], $response['SignatureValue'], $response['Shp_id'])) {
                /** @var Transaction $transaction */
                $transaction = Transaction::find($response['Shp_id']);

                error_log("Robokassa: " . print_r($transaction,true));

                if($transaction->status == 1) {
                    Transaction::notifyFailTransaction("Robokassa", $response['Shp_id']);
                }

                if ($transaction instanceof Transaction && $transaction->status == 0) {
                    $transaction->batch_id = $response['InvId'];
                    $transaction->result = 'Completed';
                    $transaction->status = 1;
                    $transaction->save();

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
                return response("OK{$response['InvId']}\n");
            } else {
                return response('Signature mismatch', 404);
            }
        } catch (\Exception $e) {
            return response('Error', 500);
        }
    }
}
