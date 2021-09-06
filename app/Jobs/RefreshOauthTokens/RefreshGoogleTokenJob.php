<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs\RefreshOauthTokens;

use App\Models\User;
use App\Models\UsersSocialMeta;
use App\Modules\SocialNetworks\YoutubeModule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class RefreshGoogleTokenJob
 * @package App\Jobs\RefreshOauthTokens
 *
 * @property User user
 */
class RefreshGoogleTokenJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var User */
    protected $user;

    /**
     * RefreshGoogleTokenJob constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $clientId       = config('services.google.client_id');
        $clientSecret   = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            \Log::info('Client id or client secret is empty for Google.');
            return $this->cleanAuthData();
        }

        $refreshToken = UsersSocialMeta::getValue($this->user, 'google_oauth_refresh_token', null);

        if (null === $refreshToken) {
            \Log::info('Extend token life: refresh token in empty for user '.$this->user->id);
            return $this->cleanAuthData();
        }

        try {
            $response = (new YoutubeModule())->sendRequest('oauth2/v4/token', 'POST', [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type'    => 'refresh_token',
            ]);
        } catch (\Exception $e) {
            $this->cleanAuthData();
            throw new \Exception($e->getMessage());
        }

        if (!isset($response->access_token) || !isset($response->expires_in)) {
            \Log::info('Access token or expire in field is not exists in Google response.');
            return $this->cleanAuthData();
        }

        UsersSocialMeta::setValue($this->user, 'google_oauth_token', $response->access_token);
        UsersSocialMeta::setValue($this->user, 'google_oauth_token_expires_in', $response->expires_in);
        UsersSocialMeta::setValue($this->user, 'google_oauth_token_refreshed', now()->toDateTimeString());

        RefreshGoogleTokenJob::dispatch($this->user)->onQueue(getSupervisorName().'-high')->delay(now()->addSeconds($response->expires_in)->subMinutes(10));
    }

    /**
     * @return bool
     */
    public function cleanAuthData()
    {
        UsersSocialMeta::setValue($this->user, 'google_oauth_code', null);
        UsersSocialMeta::setValue($this->user, 'google_oauth_token', null);
        UsersSocialMeta::setValue($this->user, 'google_oauth_token_expires_in', null);
        UsersSocialMeta::setValue($this->user, 'google_user_id', null);
        UsersSocialMeta::setValue($this->user, 'google_user_email', null);
        UsersSocialMeta::setValue($this->user, 'google_user_name', null);
        UsersSocialMeta::setValue($this->user, 'google_user_display_name', null);
        UsersSocialMeta::setValue($this->user, 'google_user_avatar', null);
        UsersSocialMeta::setValue($this->user, 'youtube_channel_id', null);

        return true;
    }
}
