<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\AddOrderController;
use App\Http\Managers\UnitpayManager;
use App\Models\Currency;
use App\Models\Package;
use App\Models\Packet;
use App\Models\PaymentSystem;
use App\Models\Promocode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\PaymentSystems\StripeModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Managers\PaymentManager;
use App\Http\Managers\UserManager;

/**
 * Class UnitpayController
 * @package App\Http\Controllers\Payment
 */
class StripeController extends Controller
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
                error_log('this_error');
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

            $path = StripeModule::createTopupInvoice($transaction);

            return view('ps.stripe', ['stripe_id' => $path]);

        } catch (\Exception $e) {
            error_log("ERROR");
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function processPayment(Request $request)
    {

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey(config('money.stripe_api_key'));

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = config('money.stripe_webhook_key');
        
        $payload = @file_get_contents('php://input');
        $fp = fopen('stripe.log', 'w+');
        fwrite($fp, $payload);
        fclose($fp);
        error_log(print_r($payload,true));
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the checkout.session.completed event
        error_log('stripe 1');
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            error_log("STRIPESESS");
            error_log(print_r($session,true));

            // if (!empty($response['transaction']['order']['id'])) {
                /** @var Transaction $transaction */

                error_log("Stripe transaction id: " . print_r($session->metadata->transaction_id,true));

                $transaction = Transaction::find($session->metadata->transaction_id);
                error_log(print_r($transaction,true));

                if($transaction->status == 1) {
                    Transaction::notifyFailTransaction("Stripe", $session->metadata->transaction_id);
                }

                if ($transaction instanceof Transaction && $transaction->status == 0) {
                    // if (empty($response['transaction']['error']['code']) && empty($response['transaction']['error']['message'])) {
                        // $transaction->source = $response['invoice']['id'];
                        $transaction->batch_id = $session->metadata->transaction_id;
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
                    // } else {
                    //     $transaction->source = $response['invoice']['id'];
                    //     $transaction->batch_id = $response['transaction']['id'];
                    //     $transaction->result = $response['transaction']['error']['code'] . ': ' . $response['transaction']['error']['message'];
                    //     $transaction->save();
                    // }
                    return response('OK', 200);
                }
            // }
            return response('Transaction does not exist', 404);
        }

        http_response_code(200);
    }

}
