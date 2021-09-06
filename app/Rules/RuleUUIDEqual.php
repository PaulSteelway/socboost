<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Rule;
use Ramsey\Uuid\Uuid;

/**
 * Class RuleUUIDEqual
 * @package App\Rules
 */
class RuleUUIDEqual implements Rule
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
        return Uuid::isValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.wrong_uuid');
    }
}
