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
use App\Modules\PaymentSystems\UnitpayModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UnitpayController
 * @package App\Http\Controllers\Payment
 */
class UnitpayController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function topUp()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $test_package = false;
            if (session('topup.test_package_id')) {
                $test_package = Package::find(session('topup.test_package_id'));
            }

            if (!$test_package) {
                if (request()->getHost() == config('app.free_url') || request()->getHost() == config('app.free_url_en')) {
                    $wallet = $user->getFreePromotionWallet();
                }else{
                    $wallet = $user->getActiveWallet();
                }
                if (empty($wallet)) {
                    $wallet = Wallet::setActiveWallet($user);
                }
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
            if (!$test_package) {
                $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $amount, $currency, $promocode_id);
                if (request()->getHost() == config('app.free_url') || request()->getHost() == config('app.free_url_')) {
                    $transaction->site_id = Transaction::FREE_BOOSTER_SITE;
                    $transaction->save();
                }
                $unitpay = UnitpayModule::getUnitpayWidgetData($transaction);
                session([Auth::id() . '_' . 'unitpay' => $unitpay]);
                return redirect()->route('profile.topup', ['amount' => $amount]);
            } else {
                $order_link = session('topup.order_link');
                $order_link = str_replace(['http://', 'https://'], '', $order_link);
                $unitpay = UnitpayModule::gerUnitpayWidgetDataForTestPackagePay($test_package, $currency, $order_link);
                session([Auth::id() . '_' . 'unitpay' => $unitpay]);
                return redirect()->route('customer.main');
            }
        } catch (\Exception $e) {
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
        try {
            $method = $request->get('method');
            $params = $request->get('params');
            if (strcasecmp($params['account'], 'test') == 0) {
                return response()->json(['result' => ['message' => 'Запрос успешно обработан']], 200);
            }

            if (strpos($params['account'], 'test_package_id') === false) {
                /** @var Transaction $transaction */

                $params_json = json_decode($params['account'], true);
                if (is_array($params_json)) {
                    $transaction_id = isset($params_json['account']) ? $params_json['account'] : '';
                    $topup_action = isset($params_json['action']) ? $params_json['action'] : '';
                } else {
                    $transaction_id = $params['account'];
                    $topup_action = '';
                }
                $transaction = Transaction::find($transaction_id);
                if ($transaction instanceof Transaction) {
                    if (strcasecmp($method, 'check') == 0) {
                        $currencies = ['RUR' => 'RUB', 'USD' => 'USD'];
                        $transactionCurrency = $currencies[$transaction->currency->code];

                        if (floatval($params['orderSum']) != floatval($transaction->amount) || $params['orderCurrency'] != $transactionCurrency) {
                            return response()->json(['error' => ['message' => 'orderSum or orderCurrency does not match payment']], 406);
                        }
                    } elseif (strcasecmp($method, 'pay') == 0) {
                        if (strcasecmp($transaction->type->name, 'subscription') == 0) {
                            UnitpayManager::processSubscriptionResponse($transaction, $params);
                        } else {
                            UnitpayManager::processTopupResponse($transaction, $params);
                            if ($topup_action == 'order') {
                                $packet = Packet::find($params_json['packet']);
                                $user = $transaction->user;
                                $add_order_controller =  new AddOrderController();
                                $add_order_controller->orderProcessing(
                                    $user, $packet, $params_json['count'], $params_json['link']
                                );
                            }
                        }
                    } elseif (strcasecmp($method, 'error') == 0) {
                        $transaction->result = 'Error';
                        $transaction->save();
                    }
                } else {
                    return response()->json(['error' => ['message' => 'Payment does not exist']], 404);
                }
            } else {
                $package_data = json_decode($params['account'], true);
                $package = Package::find($package_data['test_package_id']);
                if ($package && $package->jap_id) {
                    if (strcasecmp($method, 'pay') == 0) {
                        $r = JustanotherpanelModule::addTestPackageOrder($package, $package_data["order_link"]);
                        if (isset($r->error) && !empty($r->error)) {
                            return response()->json(['error' => ['message' => 'Произошла ошибка во время создания заказа. Детали ошибки: ' . $r->error]], 500);
                        }
                    }
                    return response()->json(['result' => ['message' => 'Запрос успешно обработан']], 200);
                } else {
                    return response()->json(['error' => ['message' => 'Package or JAP id not exist']], 500);
                }
            }

            return response()->json(['result' => ['message' => 'Запрос успешно обработан']], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => ['message' => $e->getMessage()]], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function successPayment(Request $request)
    {
//Place the order to subscription and disburse the amount of subscription
//        подписка на премиум аккаунт успешна оформлена
        if ($request->get('success') == 'order') {
            $success = __('Your order received');
            return redirect()->route('customer.main')->with('success', $success);
        }
        if ($request->get('success') == 'premium_subscription') {
            $success = __('Subscription to premium account was successfully completed');
            return redirect()->route('profile.settings')->with('success', $success);
        }
        if ($request->get('success') == 'other_subscription') {
            return redirect()->route('profile.operations.index');
        }
        $success = __('Your balance has been successfully replenished');
        return redirect()->route('profile.topup')->with('success', $success);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failPayment(Request $request)
    {
        return redirect()->route('profile.topup')->with('error', __('Failed to add funds'));
    }
}
