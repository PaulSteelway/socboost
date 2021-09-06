<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\SocialNetworks;

use App\Models\User;
use App\Models\UsersSocialMeta;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Requests\ExecuteRequest;
use ATehnix\VkClient\Requests\Request;

/**
 * Class VkModule
 * @package App\Modules\SocialNetworks
 */
class VkModule
{
    /*
     * Checking task results
     */

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \Exception
     *
     * @example https://vk.com/hyipium
     */
    public static function checkPageSubscription(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'vk_oauth_token', null);

        if (null === $userToken) {
            \Log::error('VkModule checkPageSubscription error: token id is empty for user '.$user->id);
            return false;
        }

        $api = new Client();
        $api->setDefaultToken($userToken);

        $userId = UsersSocialMeta::getValue($user, 'vk_user_id', null);

        if (null === $userId) {
            \Log::error('VkModule checkPageSubscription error: user ID is null.');
            return false;
        }

        preg_match('/vk\.com\/public([0-9]+)/', $objectUrl, $groupMatches);

        if (!isset($groupMatches[1])) {
            unset($groupMatches);
            preg_match('/vk\.com\/([A-Za-z0-9-_]+)/', $objectUrl, $groupMatches);
        }

        if (!isset($groupMatches[1])) {
            \Log::error('VkModule checkPageSubscription error: group id not found in link - '.$objectUrl);
            return false;
        }

        $groupId    = $groupMatches[1];
        $data       = [
            'group_id'  => (string) $groupId,
            'user_id'   => $userId,
        ];

        \Log::info('VkModule checkPageSubscription: prepared data '.print_r($data,true));

        $execute = ExecuteRequest::make([
            $request = new Request('groups.isMember', $data),
        ]);

        try {
            $response = $api->send($execute);
        } catch (\Exception $e) {
            \Log::error('VkModule checkPageSubscription error: '.$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        \Log::info('VkModule checkPageSubscription: got response '.print_r($response,true));

        if (!isset($response['response'][0])) {
            \Log::error('VkModule checkPageSubscription error: $response[\'response\'][0] is not found, check code.');
            return false;
        }

        return $response['response'][0] == 1
            ? true
            : false;
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \Exception
     *
     * @example https://vk.com/public170939008?w=wall-170939008_1
     */
    public static function checkPostLike(User $user, string $objectUrl)
    {
        $userToken = UsersSocialMeta::getValue($user, 'vk_oauth_token', null);

        if (null === $userToken) {
            \Log::error('VkModule checkPostLike error: token id is empty for user '.$user->id);
            return false;
        }

        $api = new Client();
        $api->setDefaultToken($userToken);

        $type = 'post';

        preg_match('/wall\-([0-9]+)\_([0-9]+)/', $objectUrl, $postMatches);

        if (!isset($postMatches[1])) {
            \Log::error('VkModule checkPostLike error: owner id not found in link - '.$objectUrl);
            return false;
        }

        if (!isset($postMatches[2])) {
            \Log::error('VkModule checkPostLike error: post id not found in link - '.$objectUrl);
            return false;
        }

        $ownerId = $postMatches[1];
        $itemId  = $postMatches[2];

        $userId = UsersSocialMeta::getValue($user, 'vk_user_id', null);

        if (null === $userId) {
            \Log::error('VkModule checkPostLike error: user ID is null.');
            return false;
        }

        $data = [
            'type'      => $type,
            'item_id'   => $itemId,
            'owner_id'  => -$ownerId,
            'user_id'   => $userId,
        ];

        \Log::info('VkModule checkPostLike: prepared data '.print_r($data,true));

        $execute = ExecuteRequest::make([
            $request = new Request('likes.isLiked', $data),
        ]);

        try {
            $response = $api->send($execute);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('VkModule checkPostLike: got response '.print_r($response,true));

        if (!isset($response['response'][0]['liked'])) {
            \Log::error('VkModule checkPostLike error: $response[0][liked] is not found, check code.');
            return false;
        }

        return $response['response'][0]['liked'] == 1
            ? true
            : false;
    }
}