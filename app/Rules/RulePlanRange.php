<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Input;
use App\Models\Rate;

/**
 * Class RulePlanRange
 * @package App\Rules
 */
class RulePlanRange implements Rule
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
     * @param  float  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $rate = Rate::find(Input::get('rate_id'));

        return $rate->min <= $value && $value <= $rate->max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.plan_range');
    }
}
