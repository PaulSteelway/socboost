<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class RuleLoginIsCorrect
 * @package App\Rules
 */
class RuleLoginIsCorrect implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ((bool) (preg_match("/^[A-Za-z0-9-_.]{3,30}$/", $value)));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Login entered incorrectly.');
    }
}
