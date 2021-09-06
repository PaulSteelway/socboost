<?php

namespace App\Http\Managers\FreePromotion;

use App\Models\User;
use App\Models\UserTasks\Tasks;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class InstagramManager
{
    /**
     * @param string $instagramUsername
     * @param string $link
     * @return bool
     * @throws \Exception
     */
    public static function checkInstagramLikeAction(string $instagramUsername, string $link)
    {
        try {

          // nit:daan Выключил, пока инста не заработала, чтоб не напрягать инсту
          
            // $shortcode = str_replace(['/p/', '/'], '', parse_url($link, PHP_URL_PATH));
            //
            // $guzzleClient = new Client();
            // $response = $guzzleClient->get('https://www.instagram.com/graphql/query/', [
            //     'query' => [
            //         'query_hash' => config('services.instagram.likes_query_hash'),
            //         'variables' => json_encode([
            //             'shortcode' => $shortcode,
            //             'include_reel' => true,
            //             'first' => 24
            //         ])
            //     ]
            // ]);
            // $data = json_decode($response->getBody()->getContents());
            // if (!empty($data->data->shortcode_media->edge_liked_by->edges)) {
            //     foreach ($data->data->shortcode_media->edge_liked_by->edges as $edg) {
            //         if (!empty($edg->node->username)) {
            //             if ($instagramUsername === $edg->node->username) {
            //                 return true;
            //             }
            //         }
            //     }
            // }
            return false;
        } catch (\Exception $e) {
            // nit:Daan Пересмотреть инстаграмм
            // \Log::error('InstagramManager.checkLikeAction: Ошибка');
            // \Log::error('InstagramManager.checkLikeAction: ' . Auth::id() . ', ' . $link . ' - ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param User $user
     * @param Tasks $task
     * @return bool
     * @throws \Exception
     */
    public static function checkInstagramFollowerAction(User $user, Tasks $task)
    {
        try {
            $guzzleClient = new Client();
            $response = $guzzleClient->get('https://www.instagram.com/graphql/query/', [
                'headers' => [
                    'cookie' => ['sessionid=' . config('services.instagram.followers_session') . ';'],
                ],
                'query' => [
                    'query_hash' => config('services.instagram.followers_query_hash'),
                    'variables' => json_encode([
                        'id' => $task->account_id,
                        'include_reel' => true,
                        'fetch_mutual' => true,
                        'first' => 24
                    ])
                ]
            ]);
            $data = json_decode($response->getBody()->getContents());
            if (!empty($data->data->user->edge_followed_by->edges)) {
                $instagramUsername = $user->userSocialProfile->instagram_username;
                foreach ($data->data->user->edge_followed_by->edges as $edg) {
                    if (!empty($edg->node->username)) {
                        if ($instagramUsername === $edg->node->username) {
                            return true;
                        }
                    }
                }
            } else {
                \Log::info('InstagramManager - checkInstagramFollowerAction: INSTAGRAM_FOLLOWERS_SESSION expired.');
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('InstagramManager.checkInstagramFollowerAction: ' . $user->id . ', ' . $task->id . ' - ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param Tasks $task
     * @return mixed
     * @throws \Exception
     */
    public static function getUserIdByUsername(Tasks $task)
    {
        $username = str_replace('/', '', parse_url($task->link, PHP_URL_PATH));

        $guzzleClient = new Client();
        $response = $guzzleClient->get('https://www.instagram.com/web/search/topsearch/', ['query' => ['query' => $username]]);
        $data = json_decode($response->getBody()->getContents());
        foreach ($data->users as $user) {
            if ($user->user->username === $username) {
                return $user->user->pk;
            }
        }
        throw new \Exception($response->getBody()->getContents());
    }
}
