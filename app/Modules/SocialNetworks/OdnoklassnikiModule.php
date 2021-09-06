<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\SocialNetworks;
use App\Models\User;
use GuzzleHttp\Client;

/**
 * Class OdnoklassnikiModule
 * @package App\Modules\SocialNetworks
 */
class OdnoklassnikiModule
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
            throw new \Exception('Youtube API request is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Youtube API response status is '.$response->getStatusCode().' for method '.$method);
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
     */
    public static function checkPageSubscription(User $user, string $objectUrl)
    {
        return false; // TODO: finish method
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     */
    public static function checkPostComment(User $user, string $objectUrl)
    {
        return false; // TODO: finish method
    }

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     */
    public static function checkPostLike(User $user, string $objectUrl)
    {
        return false; // TODO: finish method
    }
}