<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use App\Rules\RuleEnoughBalance;
use App\Rules\RuleUUIDEqual;
use App\Rules\RuleWalletExist;
use App\Rules\RuleWalletWithExternalAddress;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestWithdraw
 * @package App\Http\Requests
 *
 * @property string wallet_id
 * @property float amount
 * @property string captcha
 */
class RequestWithdraw extends FormRequest
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
            'wallet_id' => ['required', new RuleWalletExist, new RuleUUIDEqual, new RuleWalletWithExternalAddress],
            'amount'    => ['numeric', new RuleEnoughBalance],
            'captcha'   => 'required|captcha',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'wallet_id.required' => __('Wallet is required'),
            'amount.numeric'     => __('Amount have to be numeric'),
            
            'captcha.required'   => trans('validation.captcha_required'),
            'captcha.captcha'    => trans('validation.captcha_captcha'),
        ];
    }
}
