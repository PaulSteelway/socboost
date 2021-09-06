<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/perfectmoney/status',
        '/advcash/status',
        '/payeer/status',
        '/blockio/status',
        '/coinpayments/status',
        '/enpay/status',
        '/nixmoney/status',
        '/free-kassa/status',
        '/coinbase/status',
        '/coinbase/cancel',
        '/coinbase/success',
        '/paypal/success',
        '/paypal/cancel',
        '/topup/payment_message',
        '/payop/process',
        '/cloudpayments/pay',
        '/cloudpayments/fail',
        '/cloudpayments/recurrent',
        '/qiwi/process',

        '/telegram_webhook/*',
        '/youtube/watch/save/*/*',

        '/admin/logs/',
        '/admin/logs/*',
        '/auth/location',
        '/administrator2/*',
        '/my_profile_internal/process_avatar',
        '/login',
        '/stripe/process',
    ];
}
