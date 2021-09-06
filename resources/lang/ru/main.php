<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

return [
    'emails' => [
        'spam_protection' => 'Письмо не отправлено. Спам правила.',
        'sent_successfully' => 'Письмо успешно отправлено',
        'unable_to_send' => 'Невозможно отправить это письмо',

        'request' => [
            'email_required' => 'Email обязателен к заполнению',
            'email_max' => 'Максимальная длина email 255 символов',
            'email_email' => 'Некорректный формат email',

            'text_required' => 'Текст обязателен к заполнению',
            'text_min' => 'Минимальная длина текста 10 символов',
        ],
    ],
    'transaction_types' => [
        'enter' => 'Пополнение баланса',
        'withdraw' => 'Вывод средств',
        'bonus' => 'Бонус',
        'partner' => 'Партнерская комиссия',
        'dividend' => 'Заработок по депозиту',
        'create_dep' => 'Создание депозита',
        'close_dep' => 'Закрытие депозита',
        'penalty' => 'Штраф',
    ],
];
