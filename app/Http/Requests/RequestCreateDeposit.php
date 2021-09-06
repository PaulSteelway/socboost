<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use App\Rules\RuleCheckRate;
use App\Rules\RuleEnoughBalance;
use App\Rules\RulePlanRange;
use App\Rules\RuleRateCurrency;
use App\Rules\RuleWalletExist;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RuleUUIDEqual;

/**
 * Class RequestCreateDeposit
 * @package App\Http\Requests
 *
 * @property string wallet_id
 * @property string rate_id
 * @property float amount
 */
class RequestCreateDeposit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'wallet_id' => ['required', new RuleWalletExist, new RuleUUIDEqual],
            'rate_id'   => ['required', 'exists:rates,id', new RuleCheckRate, new RuleRateCurrency, new RuleUUIDEqual],
            'amount'    => ['numeric', new RuleEnoughBalance, new RulePlanRange]
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'wallet_id.required' => __('Wallet is required'),

            'rate_id.required'   => __('Rate is required'),
            'rate_id.exists'     => __('Rate is not exists'),

            'amount.numeric'     => __('Amount have to be numeric'),
        ];
    }
}
