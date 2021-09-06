<?php

namespace App\Http\Managers;

use App\Models\Order;
use App\Models\Packet;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\FollowizModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class V1APIManager
{
    /**
     * @return array
     * @throws \Exception
     */
    public function getActionBalanceResponse()
    {
        /** @var User $user */
        $user = Auth::user();
        $wallet = $user->getActiveWallet();
        return [
            'balance' => round(convertRubToUsd($wallet->balance), 2),
            'currency' => 'USD'
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getActionServicesResponse()
    {
        $services = [];
        $packets = Packet::all();
        foreach ($packets as $packet) {
            $category = $packet->category;
            if (!empty($category->status) && $category->type == 'Default') {
                $services[] = [
                    'service' => $packet->id,
                    'name' => $packet->name_en,
                    'type' => $category->type,
                    'category' => empty($category->parent_id) ? $category->name_en : "{$category->parent->name_en} / {$category->name_en}",
                    'rate' => round(convertRubToUsd($packet->price), 6),
                    'min' => $packet->min,
                    'max' => $packet->max
                ];
            }
        }
        return $services;
    }

    /**
     * @param Packet $packet
     * @param $link
     * @param $quantity
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function createOrder(Packet $packet, $link, $quantity)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Wallet $wallet */
        $wallet = $user->getActiveWallet();
        if (empty($wallet)) {
            throw ValidationException::withMessages(['balance' => 'User wallet not found.']);
        }

        $summary = $wallet->apply_discount($packet->price * $quantity);
        if ($wallet->balance < $summary) {
            throw ValidationException::withMessages(['balance' => 'You do not have enough funds in your wallet.']);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'name' => $packet->name_en,
            'quantity' => $quantity,
            'link' => $link,
            'price' => $summary,
            'packet_id' => $packet->id,
        ]);

        if (config('app.env') == 'production') {
            User::notifyAdminsViaNotificationBot('notification_bot.order_created', ['order' => $order]);
            if (empty($packet->is_manual)) {
                if ($packet->service == 'Followiz') {
                    $r = FollowizModule::addOrderByService($order);
                } else {
                    $r = JustanotherpanelModule::addOrderByService($order, $user, $packet);
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
            throw new \Exception('The service on maintenance. Please try again in 60 minutes.');
        }

        $wallet->removeAmount($summary);

        return ['order' => $order->order_id];
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getActionStatusOrderResponse($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('order_id', $orderId)->first();
        if (empty($order)) {
            return ['error' => 'Incorrect order ID'];
        } else {
            return [
                'status' => $order->jap_status
            ];
        }
    }

    /**
     * @param $orderIds
     * @return array
     */
    public function getActionStatusOrdersResponse($orderIds)
    {
        $statuses = [];
        $orderIds = explode(',', $orderIds);
        $orders = Order::where('user_id', Auth::id())->whereIn('order_id', $orderIds)->get()->pluck(null, 'order_id')->toArray();
        foreach ($orderIds as $orderId) {
            if (empty($orders[$orderId])) {
                $statuses[$orderId] = ['error' => 'Incorrect order ID'];
            } else {
                $statuses[$orderId] = [
                    'status' => $orders[$orderId]['jap_status']
                ];
            }
        }
        return $statuses;
    }
}
