<?php

return [
    'tickets' => [
        'subjects' => [
            1 => 'Order',
            2 => 'Payment',
            3 => 'Promocode',
            4 => 'Bonus',
            5 => 'Other',
        ],
        'statuses' => [
            1 => 'Under consideration',
            2 => 'Answered',
            3 => 'Closed',
        ]
    ],

    'services' => [
        1 => 'JAP',
        2 => 'Followiz',
    ],

    'order_jap_statuses' => [
        1 => 'Pending',
        2 => 'In progress',
        3 => 'Completed',
        4 => 'Partial',
        5 => 'Processing',
        6 => 'Canceled',
        7 => 'Error',
    ],

    'category_types' => [
        1 => 'Default',
        2 => 'Subscription',
    ],

    'subscription_types' => [
        1 => 'Order',
        2 => 'PremiumAccount'
    ],

    'subscription_statuses' => [
        1 => 'Active',
        2 => 'PastDue',
        3 => 'Cancelled',
        4 => 'Rejected',
        5 => 'Expired',
        6 => 'Error',
        7 => 'new',
        8 => 'active',
        9 => 'close',
        10 => 'error'
    ],

    'subscription_payment_types' => [
        'mc' => 'Мобильный платеж',
        'card' => 'Пластиковые карты',
        'webmoney' => 'WebMoney Z',
        'webmoneyWmr' => 'WebMoney R',
        'yandex' => 'Яндекс.Деньги',
        'qiwi' => 'Qiwi',
//        'paypal' => 'PayPal',
//        'alfaClick' => 'Альфа-Клик',
        'applepay' => 'Apple Pay',
    ],

    'language_codes_ru' => [
        'RU', 'UA', 'BY', 'KZ', 'MD', 'AZ', 'AM', 'TJ', 'UZ', 'ru', 'ua', 'by', 'kz', 'md', 'az', 'am', 'tj', 'uz'
    ],

    'vouchers' => [
        1 => ['id' => 1, 'price' => 1000, 'offer' => 850],
        2 => ['id' => 2, 'price' => 2000, 'offer' => 1700],
        3 => ['id' => 3, 'price' => 5000, 'offer' => 4600],
        4 => ['id' => 4, 'price' => 10000, 'offer' => 9200],
    ],

    'api_actions' => [
        1 => 'balance',
        2 => 'services',
        3 => 'add',
        4 => 'status',
    ],

];
