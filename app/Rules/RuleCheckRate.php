<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules;

use App\Models\Rate;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Input;

/**
 * Class RuleCheckRate
 * @package App\Rules
 */
class RuleCheckRate implements Rule
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
        return (Rate::find($value)->active);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.check_rate');
    }
}
