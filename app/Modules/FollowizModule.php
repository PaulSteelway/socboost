<?php

namespace App\Modules;

use App\Models\Order;
use App\Models\User;
use GuzzleHttp\Client;

/**
 * Class FollowizModule
 * @package App\Modules
 */
class FollowizModule
{
    /** @var string API_URL */
    const API_URL = 'https://followiz.com/api/v2';

    /**
     * @param array $data
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    private static function sendRequest(array $data, string $method = 'POST')
    {
        try {
            $client = new Client();
            $params = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
            ];
            if (!empty($data)) {
                $params['form_params'] = $data;
            }

            $response = $client->request($method, self::API_URL, $params);
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('FollowizModule API response status is ' . $response->getStatusCode() . ' for data: ' . json_encode($data));
            }
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw new \Exception('FollowizModule API request is failed. ' . $e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    private static function addOrder(Order $order, array $data)
    {
        $response = self::sendRequest($data);
        if (isset($response->order)) {
            $order->jap_id = $response->order;
            $order->jap_status = 'Pending';
            $order->save();
        }
        User::notifyAdminsViaNotificationBot('notification_bot.order_api_sent_followiz', ['response' => $response]);
        return $response;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws \Exception
     */
    public static function addOrderByService(Order $order)
    {
        $data = [
            'key' => config('followiz.followiz_key'),
            'action' => 'add',
            'service' => $order->packet->service_id,
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];

        error_log("Followiz order: " . print_r($data,true));

        return self::addOrder($order, $data);
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws \Exception
     */
    public static function checkOrderStatus(Order $order)
    {
        $data = [
            'key' => config('followiz.followiz_key'),
            'action' => 'status',
            'order' => $order->jap_id,
        ];

        $response = self::sendRequest($data);
        if (isset($response->status)) {
            $order->jap_status = $response->status;
            $order->save();
        } elseif (isset($response->error)) {
            $order->jap_status = 'Error';
            $order->save();
        }
        return $response;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws \Exception
     */
    public static function addOrderSubscriptionByService(Order $order)
    {
        $data = [
            'key' => config('followiz.followiz_key'),
            'action' => 'add',
            'service' => $order->packet->service_id,
            'username' => $order->username,
            'min' => $order->min,
            'max' => $order->max,
            'posts' => $order->quantity,
            'delay' => 5,
            'expiry' => $order->expiry
        ];
        return self::addOrder($order, $data);
    }
}