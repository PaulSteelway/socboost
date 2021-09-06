<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

return [
    'advcash_api_name'              => env('ADVCASH_API_NAME'),
    'advcash_account_email'         => env('ADVCASH_ACCOUNT_EMAIL'),
    'advcash_authentication_token'  => env('ADVCASH_AUTHENTICATION_TOKEN'),
    'advcash_sci_name'              => env('ADVCASH_SCI_NAME'),
    'advcash_sci_password'          => env('ADVCASH_SCI_PASSWORD'),
    'advcash_memo'                  => env('ADVCASH_MEMO', 'replenishment of balance'),
    'advcash_withdraw_memo'         => env('ADVCASH_WITHDRAW_MEMO', 'withdraw money'),

    'pm_account_id'                 => env('PM_ACCOUNT_ID'),
    'pm_account_password'           => env('PM_ACCOUNT_PASSWORD'),
    'pm_payee_account_usd'          => env('PM_PAYEE_ACCOUNT_USD'),
    'pm_payee_account_eur'          => env('PM_PAYEE_ACCOUNT_EUR'),
    'pm_payee_name'                 => env('PM_PAYEE_NAME'),
    'pm_sci_password'               => env('PM_SCI_PASSWORD'),
    'pm_memo'                       => env('PM_MEMO', 'replenishment of balance'),
    'pm_withdraw_memo'              => env('PM_WITHDRAW_MEMO', 'withdraw money'),

    'payeer_account_number'         => env('PAYEER_ACCOUNT_NUMBER'),
    'payeer_api_id'                 => env('PAYEER_API_ID'),
    'payeer_api_key'                => env('PAYEER_API_KEY'),
    'payeer_merchant_id'            => env('PAYEER_MERCHANT_ID'),
    'payeer_merchant_key'           => env('PAYEER_MERCHANT_KEY'),
    'payeer_memo'                   => env('PAYEER_MEMO', 'replenishment of balance'),
    'payeer_withdraw_memo'          => env('PAYEER_WITHDRAW_MEMO', 'withdraw money'),

    'coinpayments_public_key'       => env('COINPAYMENTS_PUBLIC_KEY'),
    'coinpayments_private_key'      => env('COINPAYMENTS_PRIVATE_KEY'),
    'coinpayments_ipn_secret'       => env('COINPAYMENTS_IPN_SECRET'),
    'coinpayments_merchant_id'      => env('COINPAYMENTS_MERCHANT_ID'),
    'coinpayments_memo'             => env('COINPAYMENTS_MEMO'),

    'nixmoney_account_id'           => env('NIXMONEY_ACCOUNT_ID'),
    'nixmoney_account_password'     => env('NIXMONEY_ACCOUNT_PASSWORD'),
    'nixmoney_wallet_usd'           => env('NIXMONEY_WALLET_USD'),
    'nixmoney_wallet_eur'           => env('NIXMONEY_WALLET_EUR'),
    'nixmoney_wallet_btc'           => env('NIXMONEY_WALLET_BTC'),
    'nixmoney_memo'                 => env('NIXMONEY_MEMO'),
    'nixmoney_withdraw_memo'        => env('NIXMONEY_WITHDRAW_MEMO'),
    'nixmoney_payee_name'           => env('NIXMONEY_PAYEE_NAME'),

    'blockio_api_key_btc'           => env('BLOCKIO_API_KEY_BTC'),
    'blockio_api_key_ltc'           => env('BLOCKIO_API_KEY_LTC'),
    'blockio_api_key_doge'          => env('BLOCKIO_API_KEY_DOGE'),
    'blockio_pin'                   => env('BLOCKIO_PIN'),
    'blockio_limit_addresses'       => env('BLOCKIO_LIMIT_ADDRESSES', 2000),

    'enpay_merchant_id'             => env('ENPAY_MERCHANT_ID'),
    'enpay_memo'                    => env('ENPAY_MEMO'),
    'enpay_api_secret_word'         => env('ENPAY_API_SECRET_WORD'),

    'waves_account_address'         => env('WAVES_ACCOUNT_ADDRESS'),
    'waves_public_key'              => env('WAVES_PUBLIC_KEY'),
    'waves_private_key'             => env('WAVES_PRIVATE_KEY'),
    'waves_hm1_testnet_wallet'      => env('WAVES_HM1_TESTNET_WALLET'),


    'coinbase_api_key'              => env('COINBASE_API_KEY'),
    'coinbase_shared_secret'        => env('COINBASE_SHARED_SECRET'),
    'coinbase_success_url'          => env('APP_URL') . env('COINBASE_SUCCESS_URL'),
    'coinbase_cancel_url'           => env('APP_URL') . env('COINBASE_CANCEL_URL'),

    'paypal_client_id'              => env('PAYPAL_CLIENT_ID'),
    'paypal_secret'                 => env('PAYPAL_SECRET'),

    'robokassa_login'               => env('ROBOKASSA_LOGIN'),
    'robokassa_password_1'          => env('ROBOKASSA_PASSWORD_1'),
    'robokassa_password_2'          => env('ROBOKASSA_PASSWORD_2'),

    'payop_public_key'            => env('PAYOP_PUBLIC_KEY'),
    'payop_secret_key'            => env('PAYOP_SECRET_KEY'),

    'cloudpayments_public_id'       => env('CLOUDPAYMENTS_PUBLIC_ID'),
    'cloudpayments_api_secret'      => env('CLOUDPAYMENTS_API_SECRET'),


    'unitpay_project_id_free'      => env('UNITPAY_PROJECT_ID_FREE'),
    'unitpay_public_key_free'      => env('UNITPAY_PUBLIC_KEY_FREE'),
    'unitpay_secret_key_free'      => env('UNITPAY_SECRET_KEY_FREE'),
    'unitpay_domain'               => env('UNITPAY_DOMAIN'),


    'qiwi_public_key'      => env('QIWI_PUBLIC_KEY'),
    'qiwi_secret_key'      => env('QIWI_SECRET_KEY'),

    'stripe_api_key'            => env('STRIPE_API_KEY'),
    'stripe_public_key'         => env('STRIPE_PUBLIC_KEY'),
    'stripe_webhook_key'        => env('STRIPE_WEBHOOK_KEY'),

    'telegram_key'              => env('TELEGRAM_PAYMENT_KEY')
];
