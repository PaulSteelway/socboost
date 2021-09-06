<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | following language lines contain default error messages used by
    | validator class. Some of these rules have multiple versions such
    | as size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute  должен быть принят.',
    'active_url' => ':attribute не валидная ссылка.',
    'after' => ':attribute должна быть дата после :date.',
    'after_or_equal' => ':attribute должна быть дата после или равна :date.',
    'alpha' => ':attribute может содержать только текст.',
    'alpha_dash' => ':attribute может содержать только текст, цифры, и тире.',
    'alpha_num' => ':attribute может содержать только текст и цифра.',
    'array' => ':attribute должен быть в списке.',
    'before' => ':attribute must be a date before :date.',
    'before_or_equal' => ':attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => ':attribute must be between :min and :max.',
        'file' => ':attribute must be between :min and :max kilobytes.',
        'string' => ':attribute must be between :min and :max characters.',
        'array' => ':attribute must have between :min and :max items.',
    ],
    'boolean' => ':attribute field must be true or false.',
    'confirmed' => ':attribute confirmation does not match.',
    'date' => ':attribute is not a valid date.',
    'date_format' => ':attribute does not match format :format.',
    'different' => ':attribute and :other must be different.',
    'digits' => ':attribute must be :digits digits.',
    'digits_between' => ':attribute must be between :min and :max digits.',
    'dimensions' => ':attribute has invalid image dimensions.',
    'distinct' => ':attribute field has a duplicate value.',
    'email' => ':attribute недействительная.',
    'exists' => 'выбранный :attribute не валидный.',
    'file' => ':attribute must be a file.',
    'filled' => ':attribute field must have a value.',
    'image' => ':attribute must be an image.',
    'in' => 'selected :attribute is invalid.',
    'in_array' => ':attribute field does not exist in :other.',
    'integer' => ':attribute must be an integer.',
    'ip' => ':attribute must be a valid IP address.',
    'ipv4' => ':attribute must be a valid IPv4 address.',
    'ipv6' => ':attribute must be a valid IPv6 address.',
    'json' => ':attribute must be a valid JSON string.',
    'max' => [
        'numeric' => ':attribute may not be greater than :max.',
        'file' => ':attribute may not be greater than :max kilobytes.',
        'string' => ':attribute may not be greater than :max characters.',
        'array' => ':attribute may not have more than :max items.',
    ],
    'mimes' => ':attribute must be a file of type: :values.',
    'mimetypes' => ':attribute must be a file of type: :values.',
    'min' => [
        'numeric' => ':attribute должен быть минимум :min.',
        'file' => ':attribute должен быть минимум :min килобайт.',
        'string' => ':attribute должен быть минимум :min символов.',
        'array' => ':attribute должен быть минимум :min позицию.',
        'text' => 'Текст должен быть минимум :min символов.',
    ],
    'not_in' => 'selected :attribute is invalid.',
    'numeric' => ':attribute must be a number.',
    'present' => ':attribute field must be present.',
    'regex' => ':attribute format is invalid.',
    'required' => ':attribute обязательное поле.',
    'required_if' => ':attribute field is required when :other is :value.',
    'required_unless' => ':attribute field is required unless :other is in :values.',
    'required_with' => ':attribute field is required when :values is present.',
    'required_with_all' => ':attribute field is required when :values is present.',
    'required_without' => ':attribute обязательное поле, если :values не указано.',
    'required_without_all' => ':attribute field is required when none of :values are present.',
    'same' => ':attribute and :other must match.',
    'size' => [
        'numeric' => ':attribute must be :size.',
        'file' => ':attribute must be :size kilobytes.',
        'string' => ':attribute must be :size characters.',
        'array' => ':attribute must contain :size items.',
    ],
    'string' => ':attribute должен быть текст.',
    'timezone' => ':attribute must be a valid zone.',
    'unique' => ':attribute  уже занят(а).',
    'uploaded' => ':attribute failed to upload.',
    'url' => ':attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'login' => 'Логин',
        'password' => 'Пароль',
        'name' => 'Имя',
        'email' => 'Электронная почта',
        'phone' => 'Телефон',
        'message' => 'Сообщение',
        'link' => 'Ссылка',
        'title' => 'Название',
        'description' => 'Описание',
        'reward_amount' => 'Оплата исполнителю',
        'execution_qty' => 'Количество выполнений',
    ],

    // Custom rule errors
    'wrong_wallet' => 'Некорректный кошелек!',
    'enough_balance' => 'Недостаточно средств на кошельке!',
    'check_rate' => 'Тарифный план недоступен!',
    'rate_currency' => 'Этот тарифный план не работает с выбранной валютой!',
    'plan_range' => 'Введенная сумма не входит в диапазон тарифного плана!',
    'wrong_uuid' => 'Некорректный ИД!',
    'no_phone' => 'номер телефона не был заполнен, либо он некорректный!',
    'external_wallet_does_not_exists' => 'Пожалуйста, заполните адрес вашего кошелька в настройках профиля.',
    'partner_id_is_not_exists' => 'Такого ID партнера не существует',
    'captcha_required' => 'Код с картинки не был введен',
    'captcha_captcha' => 'Неправильный код с картинки',
    'youtube_link' => 'Ссылка на видео должна быть в формате "https://youtu.be/XXXXXXXXXXX"',
    'video' => [
        'required_if' => 'Видео обязательно к заполнению'
    ],
    'recaptcha' => 'Необходимо подтвердить что вы не робот',
];
