<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Managers\CloudPaymentsManager;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Promocode;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\PaymentSystems\CloudPaymentsModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CloudPaymentsController
 * @package App\Http\Controllers\Payment
 */
class CloudPaymentsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
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

            $cloudpayments = CloudPaymentsModule::getTopupChargeData($transaction);

            return view('profile.topup')->with('cloudpayments', $cloudpayments);
        } catch (\Exception $e) {
            return redirect()->route('profile.topup')->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function pay(Request $request)
    {
        try {
            $response = $request->all();
            if (!empty($response['InvoiceId'])) {
                $transaction = Transaction::find($response['InvoiceId']);
                
                if($transaction->status == 0) {
                    if ($transaction instanceof Transaction) {
                        if ($transaction->type->name == 'subscription') {
                            CloudPaymentsManager::processSuccessSubscriptionInit($transaction, $response);
                        } else {
                            CloudPaymentsManager::processSuccessTopupResponse($transaction, $response);
                        }
                    }
                }
                else {
                    Transaction::notifyFailTransaction("CloudPayments", $response['InvoiceId']);
                }
            } elseif (!empty($response['SubscriptionId'])) {
                CloudPaymentsManager::processSuccessSubscriptionRecurrent($response);
            }
            return response()->json(['code' => 0], 200);
        } catch (\Exception $e) {
            \Log::error(json_encode($request->all()) . ' ----- ' . json_encode($e->getMessage()));
            return response()->json(['code' => 0], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail(Request $request)
    {
        try {
            $response = $request->all();
            if (!empty($response['InvoiceId'])) {
                $transaction = Transaction::find($response['InvoiceId']);
                if ($transaction instanceof Transaction) {
                    if ($transaction->type->name == 'subscription') {
                        CloudPaymentsManager::processFailSubscriptionInit($transaction, $response);
                    } else {
                        CloudPaymentsManager::processFailTopupResponse($transaction, $response);
                    }
                }
            } elseif (!empty($response['SubscriptionId'])) {
                CloudPaymentsManager::processFailSubscriptionRecurrent($response);
            }
            return response()->json(['code' => 0], 200);
        } catch (\Exception $e) {
            \Log::error(json_encode($request->all()) . ' ----- ' . json_encode($e->getMessage()));
            return response()->json(['code' => 0], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recurrent(Request $request)
    {
        try {
            $response = $request->all();

            $subscription = Subscription::find($response['Id']);
            if ($subscription instanceof Subscription) {
                $subscription->status = $response['Status'];
                $subscription->save();
                if ($subscription->type == 'PremiumAccount') {
                    if ($subscription->status == 'Active') {
                        $subscription->user->updatePremiumStatus(true);
                    } else {
                        $subscription->user->updatePremiumStatus(false);
                    }
                }
            }
            return response()->json(['code' => 0], 200);
        } catch (\Exception $e) {
            \Log::error(json_encode($request->all()) . ' ----- ' . json_encode($e->getMessage()));
            return response()->json(['code' => 0], 200);
        }
    }
}
