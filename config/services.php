<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

return [
    'google' => [
        'client_id'     => env('GOOGLE_OAUTH_CLIENT_ID'),
        'client_secret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
        'redirect'      => '/auth/google/callback/',
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_OAUTH_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_OAUTH_CLIENT_SECRET'),
        'redirect'      => '/auth/facebook/callback/',
    ],

    'instagram' => [
        'client_id'     => env('INSTAGRAM_KEY'),
        'client_secret' => env('INSTAGRAM_SECRET'),
        'redirect'      => '/auth/instagram/callback/',
        'auth_callback' => '/instagram/auth/callback/',
        'likes_query_hash'    => env('INSTAGRAM_LIKES_QUERY_HASH'),
        'followers_query_hash' => env('INSTAGRAM_FOLLOWERS_QUERY_HASH'),
        'followers_session' => env('INSTAGRAM_FOLLOWERS_SESSION'),
        'auth_like_url' => env('INSTAGRAM_AUTH_LIKE_URL')
    ],

    'linkedin' => [
        'client_id'     => env('LINKEDIN_KEY'),
        'client_secret' => env('LINKEDIN_SECRET'),
        'redirect'      => '/auth/linkedin/callback/'
    ],

    'vkontakte' => [
        'client_id'     => env('VKONTAKTE_OAUTH_CLIENT_ID'),
        'client_secret' => env('VKONTAKTE_OAUTH_CLIENT_SECRET'),
        'redirect'      => '/auth/vkontakte/callback/',
    ],

    'odnoklassniki' => [
        'client_id' => env('ODNOKLASSNIKI_ID'),
        'client_secret' => env('ODNOKLASSNIKI_SECRET'),
        'client_public' => env('ODNOKLASSNIKI_PUBLIC'),
        'redirect' => '/auth/odnoklassniki/callback/',
    ],

    'mailgun' => array(
        'domain' => env('MAIL_DOMAIN'),
        'secret' => env('MAIL_PASSWORD'),
    ),

    'nexmo' => [
        'key' => env('NEXMO_API_KEY'),
        'secret' => env('NEXMO_API_SECRET'),
        'sms_from' => 'socialbooster.me',
    ],
];
