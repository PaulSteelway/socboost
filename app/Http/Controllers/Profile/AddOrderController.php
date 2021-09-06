<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Managers\PaymentManager;
use App\Mail\SendAccountInfo;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Packet;
use App\Models\PaymentSystem;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Modules\FollowizModule;
use App\Modules\PaymentSystems\UnitpayModule;
use App\Modules\SocialNetworks\JustanotherpanelModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Response;

class AddOrderController extends Controller
{
    /**
     * @param Request $request
     * @param string $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, string $service)
    {
        if (!$request->has('count') || !$request->has('link')) {
            return response('broken form');
        }

        $allowed = false;
        $price = 0;
        $country = $request->has('country')
            ? $request->country
            : null;

        switch ($service) {
            case "SERVICE_ADD_ORDER_VKONTAKTE_JOIN_GROUPS";
                $price = env('SERVICE_ADD_ORDER_VKONTAKTE_JOIN_GROUPS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_ADD_FRIENDS";
                $price = env('SERVICE_ADD_ORDER_VKONTAKTE_ADD_FRIENDS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES";
                $price = env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_VIEWS";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_VIEWS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_VIEWS";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_VIEWS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_SUBSCRIBERS";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_SUBSCRIBERS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_DISLIKES";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_DISLIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_OK_GROUPS";
                $price = env('SERVICE_ADD_ORDER_OK_GROUPS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_PROFILE_FOLLOWERS";
                $price = env('SERVICE_ADD_ORDER_FACEBOOK_PROFILE_FOLLOWERS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST";
                $price = env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_TWITTER_RETWEETS_FAST";
                $price = env('SERVICE_ADD_ORDER_TWITTER_RETWEETS_FAST_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIKES";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_COMMENTS";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_COMMENTS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_AREA";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_AREA_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_HISTORY_WATCHES";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_HISTORY_WATCHES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_SAVES";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_SAVES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES";
                $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_REPOSTS";
                $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_REPOSTS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_WACHES";
                $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_WACHES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_LIKES";
                $price = env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_SUBSCRIBERS";
                $price = env('SERVICE_ADD_ORDER_TELEGRAM_SUBSCRIBERS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_TIKTOK_LIKES";
                $price = env('SERVICE_ADD_ORDER_TIKTOK_LIKES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_TIKTOK_SUBSCRIBERS";
                $price = env('SERVICE_ADD_ORDER_TIKTOK_SUBSCRIBERS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_COMMENTS";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_COMMENTS_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIVE_WATCHES";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_LIVE_WATCHES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_SHARES";
                $price = env('SERVICE_ADD_ORDER_YOUTUBE_SHARES_PRICE');
                $allowed = true;
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_IGTV";
                $price = env('SERVICE_ADD_ORDER_INSTAGRAM_IGTV_PRICE');
                $allowed = true;
                break;
        }

        if (null !== $country) {
            switch ($country) {
                case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES_PRICE');
                    $service = $country;
                    $allowed = true;
                    break;

                case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_VKONTAKTE_LIKES_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
                case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
                case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
                case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_INSTAGRAM_LIKES_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
                case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
                case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_PRICE') + env('SERVICE_ADD_ORDER_YOUTUBE_LIKES_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_PRICE') + env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_PRICE') + env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_PRICE') + env('SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_PRICE') + env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_PRICE') + env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;


                case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_PRICE') + env('SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_ALL_COUNTRIES";
                    $price = env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_ALL_COUNTRIES_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_RUSSIA";
                    $price = env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_RUSSIA_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;

                case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_UKRAINE";
                    $price = env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_PRICE') + env('SERVICE_ADD_ORDER_TELEGRAM_LIKES_UKRAINE_PRICE');
                    $allowed = true;
                    $service = $country;
                    break;
            }
        }

        if (false == $allowed) {
            return response('wrong code');
        }

        /** @var User $user */
        $user = \Auth::user();

        if (null === $user) {
            return response('not authorized');
        }


        $currency = Currency::whereIn('code', ['RUB', 'RUR'])->first();
        $paymentSystem = PaymentSystem::where('code', 'free-kassa')->first();

        if (null == $currency || null == $paymentSystem) {
            return response('payment system or currency not found');
        }

        /** @var Wallet $wallet */
        $wallet = $user->wallets()->where('payment_system_id', $paymentSystem->id)
            ->where('currency_id', $currency->id)
            ->first();

        if (null == $wallet) {
            return response('user wallet not found');
        }

        $summary = $wallet->apply_discount($price * ((int)$request->count));

        if ($summary <= 0) {
            return response('wrong price');
        }

        if ($wallet->balance < $summary) {
            return redirect()->route('profile.order_added')->with('error', __('У вас недостаточно средств на кошельке.'));
        }

        $order = Order::create([
            'user_id' => $user->id,
            'name' => $service,
            'quantity' => (int)$request->count,
            'link' => (string)$request->link,
            'price' => $summary,
        ]);

        User::notifyAdminsViaNotificationBot('notification_bot.order_created', ['order' => $order]);

        /** @var User $user */
        $user = $order->user()->first();

        switch ($order->name) {
            case "SERVICE_ADD_ORDER_VKONTAKTE_JOIN_GROUPS";
                $r = JustanotherpanelModule::addOrderVkontakteGroupJoins($order, $user);
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_ADD_FRIENDS";
                $r = JustanotherpanelModule::addOrderVkontakteAddFriends($order, $user);
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES";
                $r = JustanotherpanelModule::addOrderVkontakteLikes($order, $user);
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS";
                $r = JustanotherpanelModule::addOrderInstagramFollowers($order, $user);
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES";
                $r = JustanotherpanelModule::addOrderInstagramLikes($order, $user);
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_VIEWS";
                $r = JustanotherpanelModule::addOrderInstagramViews($order, $user);
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_VIEWS";
                $r = JustanotherpanelModule::addOrderYoutubeViews($order, $user);
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_SUBSCRIBERS";
                $r = JustanotherpanelModule::addOrderYoutubeSubscribers($order, $user);
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_DISLIKES";
                $r = JustanotherpanelModule::addOrderYoutubeDislikes($order, $user);
                break;

            case "SERVICE_ADD_ORDER_OK_GROUPS";
                $r = JustanotherpanelModule::addOrderOkGroups($order, $user);
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_PROFILE_FOLLOWERS";
                $r = JustanotherpanelModule::addOrderFacebookProfileFollowers($order, $user);
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST";
                $r = JustanotherpanelModule::addOrderFacebookLikePhotoAndPosh($order, $user);
                break;

            case "SERVICE_ADD_ORDER_TWITTER_RETWEETS_FAST";
                $r = JustanotherpanelModule::addOrderTwitterRetweetsFast($order, $user);
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIKES";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user);
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderInstagramFollowers($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA";
                $r = JustanotherpanelModule::addOrderInstagramFollowers($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE";
                $r = JustanotherpanelModule::addOrderInstagramFollowers($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderVkontakteLikes($order, $user, 'SERVICE_ADD_ORDER_VKONTAKTE_LIKES_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_RUSSIA";
                $r = JustanotherpanelModule::addOrderVkontakteLikes($order, $user, 'SERVICE_ADD_ORDER_VKONTAKTE_LIKES_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_VKONTAKTE_LIKES_UKRAINE";
                $r = JustanotherpanelModule::addOrderVkontakteLikes($order, $user, 'SERVICE_ADD_ORDER_VKONTAKTE_LIKES_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderInstagramLikes($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_LIKES_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_RUSSIA";
                $r = JustanotherpanelModule::addOrderInstagramLikes($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_LIKES_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_LIKES_UKRAINE";
                $r = JustanotherpanelModule::addOrderInstagramLikes($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_LIKES_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_LIKES_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_RUSSIA";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_LIKES_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIKES_UKRAINE";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_LIKES_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderFacebookLikePhotoAndPosh($order, $user, 'SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_RUSSIA";
                $r = JustanotherpanelModule::addOrderFacebookLikePhotoAndPosh($order, $user, 'SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_UKRAINE";
                $r = JustanotherpanelModule::addOrderFacebookLikePhotoAndPosh($order, $user, 'SERVICE_ADD_ORDER_FACEBOOK_LIKE_PHOTO_AND_POST_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_RUSSIA";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_UKRAINE";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_ALL_COUNTRIES";
                $r = JustanotherpanelModule::addOrderTelegramLikes($order, $user, 'SERVICE_ADD_ORDER_TELEGRAM_LIKES_ALL_COUNTRIES');
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_RUSSIA";
                $r = JustanotherpanelModule::addOrderTelegramLikes($order, $user, 'SERVICE_ADD_ORDER_TELEGRAM_LIKES_RUSSIA');
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_LIKES_UKRAINE";
                $r = JustanotherpanelModule::addOrderTelegramLikes($order, $user, 'SERVICE_ADD_ORDER_TELEGRAM_LIKES_UKRAINE');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_COMMENTS";
                $r = JustanotherpanelModule::addOrderInstagramComments($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_COMMENTS');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_AREA";
                $r = JustanotherpanelModule::addOrderInstagramComments($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_AREA');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_HISTORY_WATCHES";
                $r = JustanotherpanelModule::addOrderInstagramComments($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_HISTORY_WATCHES');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_SAVES";
                $r = JustanotherpanelModule::addOrderInstagramComments($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_SAVES');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_LIKES');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_REPOSTS";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_REPOSTS');
                break;

            case "SERVICE_ADD_ORDER_SOUND_CLOUD_WACHES";
                $r = JustanotherpanelModule::addOrderSoundCloudLikes($order, $user, 'SERVICE_ADD_ORDER_SOUND_CLOUD_WACHES');
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_LIKES";
                $r = JustanotherpanelModule::addOrderTelegramLikes($order, $user, 'SERVICE_ADD_ORDER_TELEGRAM_LIKES');
                break;

            case "SERVICE_ADD_ORDER_TELEGRAM_SUBSCRIBERS";
                $r = JustanotherpanelModule::addOrderTelegramLikes($order, $user, 'SERVICE_ADD_ORDER_TELEGRAM_SUBSCRIBERS');
                break;

            case "SERVICE_ADD_ORDER_TIKTOK_LIKES";
                $r = JustanotherpanelModule::addOrderTikTokLikes($order, $user, 'SERVICE_ADD_ORDER_TIKTOK_LIKES');
                break;

            case "SERVICE_ADD_ORDER_TIKTOK_SUBSCRIBERS";
                $r = JustanotherpanelModule::addOrderTikTokLikes($order, $user, 'SERVICE_ADD_ORDER_TIKTOK_SUBSCRIBERS');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_COMMENTS";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_COMMENTS');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_LIVE_WATCHES";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_LIVE_WATCHES');
                break;

            case "SERVICE_ADD_ORDER_YOUTUBE_SHARES";
                $r = JustanotherpanelModule::addOrderYoutubeLikes($order, $user, 'SERVICE_ADD_ORDER_YOUTUBE_SHARES');
                break;

            case "SERVICE_ADD_ORDER_INSTAGRAM_IGTV";
                $r = JustanotherpanelModule::addOrderInstagramComments($order, $user, 'SERVICE_ADD_ORDER_INSTAGRAM_IGTV');
                break;

            default:
                throw new \Exception('Order code is not found ' . $order->name);
        }

        if (isset($r->error) && !empty($r->error)) {
            $order->delete();
            return redirect()->route('profile.order_added')->with('error', __('Произошла ошибка во время создания заказа. Обратитесь в техническую поддержку. Детали ошибки: ' . __($r->error)));
        }

        $wallet->removeAmount($summary);

        return redirect()->route('profile.order_added')->with('success', __("Your order received"));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkout(Request $request)
    {
        try {
            $this->validate($request, [
                'packet' => 'required|exists:packets,id',
                'count' => 'required|integer',
                'link' => 'required|string|min:3'
            ]);

            /** @var Packet $packet */
            $packet = Packet::find($request->get('packet'));

            $this->validate($request, [
                'count' => [function ($attribute, $value, $fail) use ($packet) {
                    if ($value < $packet->min) {
                        $fail(__('Min count by current packet:') . ' ' . $packet->min);
                    }
                }, function ($attribute, $value, $fail) use ($packet) {
                    if ($value > $packet->max) {
                        $fail(__('Max count by current packet:') . ' ' . $packet->max);
                    }
                }, function ($attribute, $value, $fail) use ($packet) {
                    $amount = $packet->price * $value;
                    error_log("MSMSM: " . $amount);
                    if (app()->getLocale() == 'en' && convertRubToUsd($amount) < 0.5) {
                        $fail(__('Minimum sum: $0.5'));
                    }
                }]
            ]);


            


            if (Auth::check()) {
                $user = Auth::user();
                if ($request->get('successPayment')) {
                    session([Auth::id() . '_order_unfinished' => null]);
                    $this->orderProcessing($user, $packet, (int)$request->get('count'), (string)$request->get('link'));
                    $pathRedirect = null;
                } else {
                    $pathRedirect = $this->orderProcessing($user, $packet, (int)$request->get('count'), (string)$request->get('link'));
                }

                if (empty($pathRedirect)) {
                  //метка на первый заказ
                    if (Auth::user()->newOrders->count() == 1) {
                        session(['orderNew' => true]);
                    }
                    if(isset($_GET['test'])) { return (6); }
                    return redirect()->back()->with('success', __('Your order received'));
                } else {
                    //Не закончен ордер
                    session([Auth::id() . '_order_unfinished' => $request->all()]);
                    if(isset($_GET['test'])) { return (7); }

                    if (app()->getLocale() == 'en') {
                        return $pathRedirect;
                    }
                    else {
                        return redirect($pathRedirect);
                    }
                }
            } else {
                session(['autoRegister' => true]);
                if(isset($_GET['test'])) { return (8); }
                return redirect()->back()->withInput($request->input());
            }
        } catch (ValidationException $e) {
            if(isset($_GET['test'])) { return (9); }
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            error_log("GGGGG: " . $e);
            if (empty($e->getMessage())) {
              //Не удалось совершить платеж (отменено)
              if(isset($_GET['test'])) { return (10); }
                return redirect()->back()->withInput($request->input());
            } else {
              //другая ошибка
              if(isset($_GET['test'])) { return (11); }
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkoutAccountPurchase(Request $request)
    {

        try {
            $this->validate($request, [
                'product_id' => 'required|exists:products,id',
            ]);

            /** @var Product $packet */
            $product = Product::find($request->get('product_id'));


            $acc_data = $this->accountOrderProcessing($product);
            return Response::json([
                'status' => 200,
                'message' => $acc_data

            ]);
        } catch (ValidationException $e) {
            return Response::json([
                'status' => 500,
                'message' => $e->validator->errors()->getMessages()

            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 500,
                'message' => $e->getMessage()

            ]);
        }
    }

    /**
     * @param User $user
     * @param Packet $packet
     * @param $count
     * @param $link
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function orderProcessing(User $user, Packet $packet, $count, $link)
    {
        /** @var Wallet $wallet */
        $wallet = $user->getActiveWallet();

        if (empty($wallet)) {
            throw new \Exception(__('User wallet not found'));
        }
        $summary = $wallet->apply_discount($packet->price * $count, $user);

        if ($wallet->balance < $summary) {
            $widgetData = PaymentManager::getTopupWidgetDataByOrderSum($user, $summary, app()->getLocale(),
                ['action'=>'order', 'packet'=>$packet->id, 'count'=>$count, 'link'=>$link]
            );
            if (app()->getLocale() == 'en') {
                return view('ps.stripe', ['stripe_id' => $widgetData]);
            } else {
                session([Auth::id() . '_autoSubmitOrder' => $widgetData]);
                return $widgetData;
                //throw new \Exception('');
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'name' => app()->getLocale() == 'en' ? $packet->name_en : $packet->name_ru,
            'quantity' => $count,
            'link' => $link,
            'price' => $summary,
            'packet_id' => $packet->id,
        ]);

        User::notifyAdminsViaNotificationBot('notification_bot.order_created', ['order' => $order]);

        if (empty($packet->is_manual)) {
            if ($packet->service == 'Followiz') {
                $r = FollowizModule::addOrderByService($order);
            } else {
                $r = JustanotherpanelModule::addOrderByService($order, $user, $packet);
            }
        } else {
            $order->jap_status = 'Pending';
            $order->save();
        }

        if (!empty($r->error)) {
            error_log("ERR 1: " . $r->error);
            $order->delete();
           // throw new \Exception(__('Произошла ошибка во время создания заказа. Обратитесь в техническую поддержку. Детали ошибки: ' . __($r->error)));
            throw new \Exception(__('The service on maintenance. Please try again in 60 minutes.'));
        }

        $wallet->removeAmount($summary);

        return;
    }

    /**
     * @param Packet $product
     * @param $count
     * @param $link
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    private function accountOrderProcessing(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();
        if (empty($user)) {
            throw new \Exception(__('User not authorized'));
        }

        /** @var Wallet $wallet */
        $wallet = $user->getActiveWallet();

        if (empty($wallet)) {
            throw new \Exception(__('User wallet not found'));
        }


        $summary = $wallet->apply_discount($product->price);

        if ($wallet->balance < $summary) {
            $widgetData = PaymentManager::getTopupWidgetDataByOrderSum($user, $summary, app()->getLocale());
            if (app()->getLocale() == 'en') {
                error_log('tstst');
                return $widgetData;
            } else {
                return ['widget'=>$widgetData];
            }
        }
        if($product->productItems->isEmpty()){
            throw new \Exception(__('No account left'));
        }

        $order = Order::create([
            'user_id' => $user->id,
            'name' => app()->getLocale() == 'en' ? $product->name_en : $product->name_ru,
            'price' => $summary,
            'type' => 2,
            'product_id' => $product->id,
        ]);

        User::notifyAdminsViaNotificationBot('notification_bot.order_created', ['order' => $order]);

        $productItem = $product->productItems[0];

        $userdata =  $productItem->username;
        if (str_contains($userdata,'::') && str_contains($userdata,';;')) {
            $parting = explode(';;', $userdata);
            $full_parsing = [];
            foreach (array_filter($parting) as $value){
                $full_parsing[] = explode('::', $value);
            }
        }


        $res = Mail::to(Auth::user())->send(new SendAccountInfo(Auth::user(), $productItem));

        if (!empty($res->error)) {
            error_log("ERR 2: " . $res->error);
            $order->delete();
            throw new \Exception(__('The service on maintenance. Please try again in 60 minutes.'));
        }
        $productItem->user_id = Auth::id();
        $productItem->save();
        $wallet->proceedAccountPurchase($summary);
        $productItem->delete();
        return $userdata;
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function check_balance(Request $request)
    {
        /** @var Wallet $wallet */
        $wallet = Auth::user()->getActiveWallet();
        if ($wallet instanceof Wallet) {
            $summary = $wallet->apply_discount($request->get('price') * $request->get('qty'));
            if ($wallet->balance < $summary) {
                $shortage = ceil(socialboosterPriceByAmount($summary - $wallet->balance));
                $link = route('profile.topup', ['amount' => $shortage]);
                return Response::json(['link' => $link]);
            }
        }
        return Response::json([]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkoutSubscription(Request $request)
    {
        try {
            $this->validate($request, [
                'packet' => 'required|exists:packets,id',
                'paymentMethod' => 'required|in:' . implode(',', array_keys(config('enumerations.subscription_payment_types'))),
                'username' => 'required|string',
                'posts' => 'required|integer|min:1',
                'qtyMin' => 'required|integer|lte:qtyMax',
                'qtyMax' => 'required|integer|gte:qtyMin'
            ]);

            /** @var Packet $packet */
            $packet = Packet::find($request->get('packet'));
            $this->validate($request, [
                'qtyMin' => [function ($attribute, $value, $fail) use ($packet) {
                    if ($value < $packet->min) {
                        $fail(__('Min count by current packet:') . ' ' . $packet->min);
                    }
                }],
                'qtyMax' => [function ($attribute, $value, $fail) use ($packet) {
                    if ($value > $packet->max) {
                        $fail(__('Max count by current packet:') . ' ' . $packet->max);
                    }
                }]
            ]);

            if (Auth::check()) {
                $path = $this->subscriptionProcessing(
                    $packet,
                    (string)$request->get('paymentMethod'),
                    $request->ip(),
                    (string)$request->get('username'),
                    (int)$request->get('posts'),
                    (int)$request->get('qtyMin'),
                    (int)$request->get('qtyMax')
                );
                return redirect($path);
            } else {
                session(['autoRegister' => true]);
                return redirect()->back()->withInput($request->input());
            }
        } catch (ValidationException $e) {
            $messages = $e->validator->errors()->getMessages();
            if (!empty($messages['qtyMin']) && in_array('validation.lte.numeric', $messages['qtyMin'])) {
                $key = array_search('validation.lte.numeric', $messages['qtyMin']);
                $messages['qtyMin'][$key] = __('Quantity Min can not be greater than Quantity Max');
            }
            if (!empty($messages['qtyMax']) && in_array('validation.gte.numeric', $messages['qtyMax'])) {
                $key = array_search('validation.gte.numeric', $messages['qtyMax']);
                $messages['qtyMax'][$key] = __('Quantity Max can not be less than Quantity Min');
            }
            return redirect()->back()->withInput($request->input())->withErrors($messages);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * @param Packet $packet
     * @param $paymentMethod
     * @param $ip
     * @param $username
     * @param $posts
     * @param $qtyMin
     * @param $qtyMax
     * @return mixed
     * @throws \Exception
     */
    private function subscriptionProcessing(Packet $packet, $paymentMethod, $ip, $username, $posts, $qtyMin, $qtyMax)
    {
        $code = app()->getLocale() == 'en' ? 'USD' : 'RUR';
        /** @var Currency $currency */
        $currency = Currency::where('code', $code)->first();

        /** @var Subscription $subscription */
        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'type' => 'Order',
            'currency_id' => $currency->id,
            'packet_id' => $packet->id,
            'payment_method' => $paymentMethod,
            'ip' => $ip,
            'username' => $username,
            'posts' => $posts,
            'qty_min' => $qtyMin,
            'qty_max' => $qtyMax
        ]);

        /** @var Transaction $transaction */
        $transaction = Transaction::subscriptionOrder($subscription);

        return UnitpayModule::sendSubscriptionInitRequest($subscription, $transaction);

//        $cloudpayments = CloudPaymentsModule::getOrderSubscriptionChargeData($transaction, $subscription);
//        session([Auth::id() . '_' . 'cloudpayments' => $cloudpayments]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function premiumPurchase(Request $request)
    {
        try {
            $code = app()->getLocale() == 'en' ? 'USD' : 'RUR';
            /** @var Currency $currency */
            $currency = Currency::where('code', $code)->first();

            /** @var Subscription $subscription */
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'type' => 'PremiumAccount',
                'currency_id' => $currency->id,
                'payment_method' => 'card',
                'ip' => $request->ip(),
            ]);

            /** @var Transaction $transaction */
            $transaction = Transaction::subscriptionPremiumAccount($subscription);

            $path = UnitpayModule::sendSubscriptionInitRequest($subscription, $transaction);

            return redirect($path);

//            $cloudpayments = CloudPaymentsModule::getPremiumAccountSubscriptionChargeData($transaction, $subscription);
//            session([Auth::id() . '_' . 'cloudpayments' => $cloudpayments]);
//            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
}
