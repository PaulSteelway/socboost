<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class RulePartnerIdExists
 * @package App\Rules
 */
class RulePartnerIdExists implements Rule
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
        return User::where('my_id', $value)->get()->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.partner_id_is_not_exists');
    }
}
