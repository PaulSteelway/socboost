<?php

namespace App\Http\Managers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\Packet;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\PaymentSystems\CloudPaymentsModule;
use App\Modules\PaymentSystems\StripeModule;
use App\Modules\PaymentSystems\RobokassaModule;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class PaymentManager
{
    /**
     * @param Transaction $transaction
     * @param float $amount
     * @return float
     */
    public static function checkTransactionForPromocode(Transaction $transaction, $amount)
    {
        if ($transaction->promocode && Promocodes::check($transaction->promocode->code)) {
            $promocode = $transaction->promocode;
            if ($promocode->data['apply_from'] <= $amount) {
                $amount += $promocode->reward;
                $promocode->is_disposable--;
                $promocode->save();
            }
        }
        return $amount;
    }

    /**
     * @param Order $order
     */
    public static function refundByCanceledOrder(Order $order)
    {
        /** @var Wallet $wallet */
        $wallet = $order->user->getActiveWallet();
        $wallet->refundAmount($order->price, 'Order', $order->order_id);
    }

    /**
     * @param User $user
     * @param $amount
     * @param $locale
     * @param array $additional_array
     * @return array|string
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public static function getTopupWidgetDataByOrderSum(User $user, $amount, $locale, array $additional_array=[])
    {
        if ($locale == 'en') {
            $currency = Currency::where('code', 'USD')->first();
            $amount = convertRubToUsd($amount);
            $amount = PaymentManager::priceRound($amount);

            /** @var PaymentSystem $paymentSystem */
            $paymentSystem = PaymentSystem::where('code', 'stripe')->first();
            $transaction = Transaction::enterByParameters($user->getActiveWallet(), $paymentSystem, $amount, $currency);

            return StripeModule::createTopupInvoice($transaction, URL::previous() . '?autoSubmitOrder=1', route('customer.main'));
        } else {
            // new Robokassa 
            $currency = Currency::where('code', 'RUR')->first();
            /** @var PaymentSystem $paymentSystem */
            $amount = PaymentManager::priceRound($amount);

            $paymentSystem = PaymentSystem::where('code', 'robokassa')->first();
            $transaction = Transaction::enterByParameters($user->getActiveWallet(), $paymentSystem, $amount, $currency);
            return RobokassaModule::getRobokassaWidgetData($transaction, $additional_array);
        }
    }

    /**
     * @param User $user
     * @param object $data
     * @return array
     * @throws ValidationException|\Exception
     */
    public static function setOrderDataByUnregisteredUser(User $user, $data)
    {
        /** @var Packet $packet */
        $packet = Packet::find($data['packet']);
        if (empty($packet)) {
            throw ValidationException::withMessages(['packet' => ['Packet is invalid']]);
        }

        if ($packet->category->type == 'Subscription') {
            $widgetData = null;
        } else {
            if (empty($data['count'])) {
                throw ValidationException::withMessages(['order' => ['Order data is invalid']]);
            }
            $amount = $packet->price * $data['count'];

            // if (app()->getLocale() == 'en' && convertRubToUsd($amount) < 0.5) {
            //     throw ValidationException::withMessages(['order' => ['Minimum sum $0.5']]);
            // }

            $additional_array = [];
            if(!empty($data['count'] && !empty($data['link']))) {
                $additional_array = ['action'=>'order', 'packet'=>$data['packet'], 'count'=>$data['count'],
                    'link'=>$data['link']];
            }
            $widgetData = self::getTopupWidgetDataByOrderSum($user, $amount, app()->getLocale(), $additional_array);
        }
        session([Auth::id() . '_order_unfinished' => $data]);


        return $widgetData;
    }

    public static function priceRound($price) {
        return (floor($price * 100) / 100.0) + 0.01;
    }
}