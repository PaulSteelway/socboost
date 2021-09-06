<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules\Telegram;

use App\Models\Telegram\TelegramBots;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class RuleTelegramKeywordExists
 * @package App\Rules\Telegram
 */
class RuleTelegramKeywordExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, TelegramBots::getExistsKeywords());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Telegram bot keyword is not exists');
    }
}
