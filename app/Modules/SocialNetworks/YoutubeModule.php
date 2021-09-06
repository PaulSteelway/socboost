<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\SocialNetworks;

use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramUsers;
use App\Models\User;
use App\Models\UsersSocialMeta;
use GuzzleHttp\Client;

/**
 * Class YoutubeModule
 * @package App\Modules\SocialNetworks
 */
class YoutubeModule
{
    /** @var string $api */
    private $api = 'https://www.googleapis.com/';

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
            throw new \Exception('Youtube API request is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Youtube API response status is '.$response->getStatusCode().' for method '.$method);
        }

        $body = json_decode($response->getBody()->getContents());

        return $body;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkVideoLike(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'google_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Youtube checkVideoLike error: token id is empty for user '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkVideoLike - Telegram user not found');
            return false;
        }

        if (!preg_match('/\/watch\?v\=([A-Za-z0-9-_]+)/', $objectUrl, $videoId)) {
            \Log::info('checkVideoLike - wrong video link');
            return false;
        }

        if (!isset($videoId[1])) {
            \Log::info('checkVideoLike - video id is not found');
            return false;
        }

        $videoId = $videoId[1];

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkVideoLike - Bot not found');
            return false;
        }

        try {
            $response = (new YoutubeModule())->sendRequest('youtube/v3/videos/getRating?id='.$videoId.'&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            return false;
        }

        \Log::info('checkVideoLike - Got response '.print_r($response,true));

        if (!isset($response->items[0]->rating)) {
            \Log::info('checkVideoLike - items not found');
        }

        return $response->items[0]->rating == 'like' ? true : false;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkChannelSubscription(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'google_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Youtube checkChannelSubscription error: token id is empty for user '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkChannelSubscription - Telegram user not found');
            return false;
        }

        if (!preg_match('/\/channel\/([A-Za-z0-9-_]+)/', $objectUrl, $channelId)) {
            \Log::info('checkChannelSubscription - wrong channel link');
            return false;
        }

        if (!isset($channelId[1])) {
            \Log::info('checkChannelSubscription - channel id is not found');
            return false;
        }

        $channelId      = $channelId[1];
        $userChannelId  = UsersSocialMeta::getValue($user, 'youtube_channel_id', null);

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkChannelSubscription - Bot not found');
            return false;
        }

        try {
            $response = (new YoutubeModule())->sendRequest('youtube/v3/subscriptions?part=snippet&forChannelId='.$channelId.'&mine=true&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('checkChannelSubscription - Got response '.print_r($response,true));

        if (!isset($response->items) || 0 == count($response->items)) {
            \Log::info('checkChannelSubscription - items not found');
        }

        \Log::info('checkChannelSubscription - checking all subscriptions with channel id - '.$channelId);

        foreach ($response->items as $item) {
            if (!isset($item->snippet->resourceId->channelId)) {
                continue;
            }

            if ($item->snippet->resourceId->channelId == $channelId) {
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
     */
    public static function checkVideoComment(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'google_oauth_token', null);

        if (null === $userToken) {
            \Log::error('Youtube checkVideoComment error: token id is empty for user '.$user->id);
            return false;
        }

        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('checkVideoComment - Telegram user not found');
            return false;
        }

        if (!preg_match('/\/watch\?v\=([A-Za-z0-9-_]+)/', $objectUrl, $videoId)) {
            \Log::info('checkVideoLike - wrong video link');
            return false;
        }

        if (!isset($videoId[1])) {
            \Log::info('checkVideoComment - video id is not found');
            return false;
        }

        $videoId        = $videoId[1];
        $userChannelId  = UsersSocialMeta::getValue($user, 'youtube_channel_id', null);

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('checkVideoComment - Bot not found');
            return false;
        }

        try {
            $response = (new YoutubeModule())->sendRequest('youtube/v3/commentThreads?part=snippet&videoId='.$videoId.'&maxResults=100&textFormat=plainText&access_token='.$userToken, 'GET');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('checkVideoComment - Got response '.print_r($response,true));

        if (!isset($response->items) || 0 == count($response->items)) {
            \Log::info('checkVideoComment - items not found');
        }

        \Log::info('checkVideoComment - checking all comments with video id - '.$videoId);

        foreach ($response->items as $item) {
            if (!isset($item->snippet->topLevelComment->snippet->authorChannelId->value)) {
                continue;
            }

            $authorChannelId    = $item->snippet->topLevelComment->snippet->authorChannelId->value;
            $authorText         = $item->snippet->topLevelComment->snippet->textDisplay;

            if ($authorChannelId == $userChannelId
                && strlen($authorText) >= 75) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     */
    public static function checkVideoWatch(User $user, string $objectUrl)
    {
        return $user->youtubeVideoWatches()
                ->where('resource_url', $objectUrl)
                ->count() > 0;
    }
}