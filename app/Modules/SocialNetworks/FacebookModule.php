<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\SocialNetworks;

use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramUsers;
use App\Models\User;
use App\Models\UsersSocialMeta;
use GuzzleHttp\Client;

/**
 * Class FacebookModule
 * @package App\Modules\SocialNetworks
 */
class FacebookModule
{
    /** @var string $api */
    private $api = 'https://graph.facebook.com';

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
//        $headers  = [
//            'Content-Type' => 'application/json'
//        ];
        $verify   = config('app.env') == 'production' ? true : false;
        $params   = [
//            'headers' => $headers,
            'verify'  => $verify,
        ];

        if (!empty($data)) {
            $params['json'] = $data;
        }

        try {
            $response = $client->request($type, $baseUrl.$method, $params);
        } catch (\Exception $e) {
            throw new \Exception('Facebook API request is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Facebook API response status is '.$response->getStatusCode().' for method '.$method);
        }

        $body = json_decode($response->getBody()->getContents());

        return $body;
    }

    /*
     * Checking task results
     */

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkPageLike(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'facebook_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Facebook checkPageLike error: token id is empty for user '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkPageLike - Telegram user not found');
            return false;
        }

        if (!preg_match('/\/([0-9]+)/', $objectUrl, $pageId)) {
            \Log::info('checkPageLike - wrong page id');
            return false;
        }

        if (!isset($pageId[1])) {
            \Log::info('checkPageLike - page id is not found');
            return false;
        }

        $pageId = $pageId[1];

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkPageLike - Bot not found');
            return false;
        }

        try {
            $response = (new FacebookModule())->sendRequest('/me?fields=likes&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('checkPageLike - Got response '.print_r($response,true));

        if (!isset($response->likes->data[0])) {
            \Log::info('checkPageLike - items not found');
        }

        \Log::info('checkPageLike - checking all likes with page name - '.$pageId);

        foreach ($response->likes->data as $like) {
            if (!isset($like->id)) {
                continue;
            }

            if ($like->id == $pageId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * example: https://www.facebook.com/profile.php?id=100022790422877
     */
    public static function checkNewFriends(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'facebook_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Facebook checkNewFriends error: token id is empty for user '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkNewFriends - Telegram user not found');
            return false;
        }

        if (!preg_match('/\?id\=([0-9]+)/', $objectUrl, $friendId)) {
            \Log::info('checkNewFriends - wrong friend id');
            return false;
        }

        if (!isset($friendId[1])) {
            \Log::info('checkNewFriends - friend id is not found');
            return false;
        }

        $friendId = $friendId[1];

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkNewFriends - Bot not found');
            return false;
        }

        try {
            $response = (new FacebookModule())->sendRequest('/me?fields=friends{id}&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('checkNewFriends - Got response '.print_r($response,true));

        if (!isset($response->friends->data[0])) {
            \Log::info('checkNewFriends - items not found');
        }

        \Log::info('checkNewFriends - checking all friends with page id - '.$friendId);

        foreach ($response->friends->data as $friend) {
            if (!isset($friend->id)) {
                continue;
            }

            if ($friend->id == $friendId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * // https://developers.facebook.com/docs/graph-api/reference/v3.1/object/likes
     */
    public static function checkPostComment(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'facebook_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Facebook checkPostComment error: token id is empty for user '.$user->id);
            return false;
        }

        $userId = UsersSocialMeta::getValue($user, 'facebook_user_id', null);

        if (null === $userId) {
            \Log::error('Facebook checkPostComment error: user ID is empty '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkPostComment - Telegram user not found');
            return false;
        }

        if (!preg_match('/\/([0-9]+)\/posts\/([0-9]+)/', $objectUrl, $post)) {
            \Log::info('checkPostComment - wrong post data');
            return false;
        }

        if (!isset($friendId[1]) || !isset($friendId[2])) {
            \Log::info('checkPostComment - post data not found');
            return false;
        }

        $pageId = $post[1];
        $postId = $post[2];

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkPostComment - Bot not found');
            return false;
        }

        try {
            $response = (new FacebookModule())->sendRequest('/'.$pageId.'_'.$postId.'/comments&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('checkPostComment - Got response '.print_r($response,true));

        if (!isset($response->data[0])) {
            \Log::info('checkPostComment - items not found');
        }

        \Log::info('checkPostComment - checking all comments with post id - '.$postId.' and page id '.$pageId);

        foreach ($response->data as $comment) {
            if (!isset($comment->from->id) || !isset($comment->message)) {
                continue;
            }

            if ($comment->from->id == $userId
             && strlen($comment->message) >= 10) {
                return true;
            }
        }

        return false;
    }
}