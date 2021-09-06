<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Providers;

use App\Listeners\LoggingListener;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\TranslationPublishedEvent' => [
            'App\Listeners\TranslationActionsListener',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\NotificationPasswordReset',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \App\Providers\Socialite\Google\GoogleExtendSocialite::class,
            'SocialiteProviders\\Facebook\\FacebookExtendSocialite@handle',
            'SocialiteProviders\\Instagram\\InstagramExtendSocialite@handle',
            'SocialiteProviders\\LinkedIn\\LinkedInExtendSocialite@handle',
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
            'SocialiteProviders\\Odnoklassniki\\OdnoklassnikiExtendSocialite@handle',
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentEmail',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
