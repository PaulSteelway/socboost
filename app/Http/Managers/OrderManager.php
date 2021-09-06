<?php

namespace App\Http\Managers;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Modules\FollowizModule;
use App\Modules\PaymentSystems\CloudPaymentsModule;
use App\Modules\PaymentSystems\UnitpayModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Carbon\Carbon;

class OrderManager
{
    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createSubscriptionUnitpayOrder(Subscription $subscription, Transaction $transaction)
    {
        $result = UnitpayModule::getSubscriptionDetails($subscription);
        if (!empty($result->error->message)) {
            $subscription->status = 'error';
            $subscription->note = $result->error->message;
            $subscription->save();
        } else {
            $subscription->status = $result->result->status;
            $subscription->save();

            if ($subscription->status == 'active') {
                self::createExternalOrder($subscription, $transaction);
            } else {
                $subscription->note = 'Order can not be ordered - Subscription is not "active"';
                $subscription->save();
            }
        }
    }


    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public static function createSubscriptionCloudpaymentsOrder(Subscription $subscription, Transaction $transaction)
    {
        $result = CloudPaymentsModule::getSubscriptionDetails($subscription);
        if ($result->Success) {
            $subscription->status = $result->Model->Status;
            $subscription->date_at = strtotime($result->Model->NextTransactionDateIso);
            $subscription->save();

            if ($result->Model->Status == 'Active') {
                self::createExternalOrder($subscription, $transaction);
            } else {
                $subscription->note = json_encode($result);
                $subscription->save();
            }
        } else {
            $subscription->status = empty($result->Model->Status) ? 'Error' : $result->Model->Status;
            $subscription->note = json_encode($result);
            $subscription->save();
        }
    }


    /**
     * @param Subscription $subscription
     * @param Transaction $transaction
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    private static function createExternalOrder(Subscription $subscription, Transaction $transaction)
    {
        $order = Order::create([
            'user_id' => $subscription->user->id,
            'subscription_id' => $subscription->id,
            'packet_id' => $subscription->packet->id,
            'name' => $subscription->currency->code == 'USD' ? $subscription->packet->name_en : $subscription->packet->name_ru,
            'username' => $subscription->username,
            'quantity' => $subscription->posts,
            'min' => $subscription->qty_min,
            'max' => $subscription->qty_max,
            'expiry' => (new Carbon($subscription->date_at))->format('d/m/Y'),
            'price' => $transaction->currency->code == 'USD' ? convertUsdToRub($transaction->amount) : $transaction->amount,
        ]);

        if (config('app.env') == 'production') {
            User::notifyAdminsViaNotificationBot('notification_bot.order_created', ['order' => $order]);
            if (empty($subscription->packet->is_manual)) {
                if ($subscription->packet->service == 'Followiz') {
                    $r = FollowizModule::addOrderSubscriptionByService($order);
                } else {
                    $r = JustanotherpanelModule::addOrderSubscriptionByService($order);
                }
            }
        } else {
            $r = (object)['order' => rand(100000000, 999999999) . '_test'];
            $order->jap_id = $r->order;
            $order->jap_status = 'Pending';
            $order->save();
        }

        if (!empty($r->error)) {
            $order->delete();
            $subscription->note = $r->error;
            $subscription->save();
        }
    }
}