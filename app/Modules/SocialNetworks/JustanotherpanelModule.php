<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\SocialNetworks;

use App\Models\Order;
use App\Models\Packet;
use App\Models\User;
use GuzzleHttp\Client;

/**
 * Class JustanotherpanelModule
 * @package App\Modules\SocialNetworks
 */
class JustanotherpanelModule
{
    /** @var string $api */
    private $api = 'https://justanotherpanel.com/api/v2';

    /**
     * @param string $method
     * @param string|null $type
     * @param array|null $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(string $method, string $type=null, array $data=null)
    {
        if (null === $type) {
            $type = 'GET';
        }

        if (null === $data) {
            $data = [];
        }

        $client   = new Client();
        $baseUrl  = $this->api;
        $headers  = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $verify   = config('app.env') == 'production' ? true : false;
        $params   = [
            'headers' => $headers,
            'verify'  => $verify,
        ];

        if (!empty($data)) {
            $params['form_params'] = $data;
        }

        try {
            $response = $client->request($type, $baseUrl.$method, $params);
        } catch (\Exception $e) {
            throw new \Exception('JustanotherpanelModule API request is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('JustanotherpanelModule API response status is '.$response->getStatusCode().' for method '.$method);
        }

        $body = json_decode($response->getBody()->getContents());

        return $body;
    }

    /**
     * @param Order $order
     * @param User $user
     * @param array $data
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrder(Order $order, User $user, array $data)
    {
        try {
            $response = (new JustanotherpanelModule())->sendRequest('', 'POST', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        User::notifyAdminsViaNotificationBot('notification_bot.order_api_sent', ['response' => $response]);

        if (isset($response->order)) {
            $order->jap_id = $response->order;
            $order->jap_status = 'Pending';
            $order->save();
            return $response;
        }

        return $response;
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderVkontakteGroupJoins(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => (null !== $service ? env($service, 0) : null) !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_VKONTAKTE_JOIN_GROUPS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderVkontakteAddFriends(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_VKONTAKTE_ADD_FRIENDS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderVkontakteLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderTelegramSubscribers(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_TELEGRAM_SUBSCRIBERS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderTelegramLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_TELEGRAM_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderInstagramFollowers(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderInstagramLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderInstagramComments(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_INSTAGRAM_COMMENTS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addTestPackageOrder($package, $link)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => $package->jap_id,
            'link' => $link,
            'quantity' => $package->qty,
        ];
        $response = (new JustanotherpanelModule())->sendRequest('', 'POST', $data);

        User::notifyAdminsViaNotificationBot('notification_bot.test_package_api_sent', ['response' => $response]);

        return $response;
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderInstagramViews(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_INSTAGRAM_VIEWS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderYoutubeViews(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_YOUTUBE_VIEWS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderYoutubeSubscribers(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_YOUTUBE_SUBSCRIBERS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderYoutubeLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_YOUTUBE_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderYoutubeDislikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_YOUTUBE_DISLIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderOkGroups(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_OK_GROUPS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderFacebookProfileFollowers(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_FACEBOOK_PROFILE_FOLLOWERS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderFacebookLikePhotoAndPosh(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderSoundCloudWatches(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_SOUND_CLOUD_WACHES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderSoundCloudLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderSoundCloudReposts(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_SOUND_CLOUD_REPOSTS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderTikTokLikes(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_TIKTOK_LIKES', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderTikTokSubscribers(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_TIKTOK_SUBSCRIBERS', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderTwitterRetweetsFast(Order $order, User $user, $service=null)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => null !== $service ? env($service, 0) : env('SERVICE_ADD_ORDER_TWITTER_RETWEETS_FAST', 0),
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];
        return self::addOrder($order, $user, $data);
    }

    /**
     * @param Order $order
     * @param User $user
     * @param Packet $packet
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderByService(Order $order, User $user, Packet $packet)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => $packet->service_id,
            'link' => $order->link,
            'quantity' => $order->quantity,
        ];

        error_log("Justanotherpanel order: " . print_r($data,true));

        $response = self::addOrder($order, $user, $data);

        if(!isset($response->error)) {
            return $response;
        }

        // --- DEBUG
        $message = "Data: " . print_r($data,true);

        // check current service
        $api = 'https://justanotherpanel.com/api/v2';

        $ch = curl_init($api);

        $data = [
            'key' => 'cb922d8b2b442ffe2c071dcbd480fef1',
            'action' => 'services',
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);

        $res = json_decode($res,true);
        $index = array_search(5696, array_column($res,'service'));

        $message .= PHP_EOL . PHP_EOL . "service: " . print_r($res[$index],true);

        curl_close($ch);

    // check balance
        $api = 'https://justanotherpanel.com/api/v2';

        $ch = curl_init($api);

        $data = [
            'key' => 'cb922d8b2b442ffe2c071dcbd480fef1',
            'action' => 'balance',
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);

        $res = json_decode($res,true);

        $message .= PHP_EOL . PHP_EOL . "balance: " . print_r($res,true);

        curl_close($ch);

        error_log("TELEGRAM: " . $message);

        $api = 'https://api.telegram.org/bot1027814969:AAF6966DS18jPW1hldUpcTG3OjpnyZXx7jc/sendMessage';

        $ch = curl_init($api);

        $data = [
            'parse_mode' => 'HTML',
            'chat_id' => '-1001393890479',
            'text'  => $message
        ];

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkOrderStatus(Order $order)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'status',
            'order' => $order->jap_id,
        ];

        $response = (new JustanotherpanelModule())->sendRequest('', 'POST', $data);
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
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function addOrderSubscriptionByService(Order $order)
    {
        $data = [
            'key' => env('JUSTANOTHERPANEL_KEY'),
            'action' => 'add',
            'service' => $order->packet->service_id,
            'username' => $order->username,
            'min' => $order->min,
            'max' => $order->max,
            'posts' => $order->quantity,
            'delay' => 5,
            'expiry' => $order->expiry
        ];
        return self::addOrder($order, $order->user, $data);
    }
}
