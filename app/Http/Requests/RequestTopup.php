<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestTopup
 * @package App\Http\Requests
 *
 * @property string currency
 * @property float amount
 * @property string captcha
 */
class RequestTopup extends FormRequest
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
            'currency' => 'required|max:255|string',
            'amount'   => 'required|regex:/^\d*(\.\d{1,8})?$/|min:0.00000001',
//            'captcha'  => 'required|captcha',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'currency.required' => __('Currency is required.'),
            'currency.max'      => __('Currency maximum string length is 255 symbols.'),
            'currency.string'   => __('Currency have to be string.'),

            'amount.required'   => __('Amount is required.'),
            'amount.regex'      => __('Wrong amount format.'),
            'amount.min'        => __('Minimum amount is').' 0.00000001',

//            'captcha.required'  => trans('validation.captcha_required'),
//            'captcha.captcha'   => trans('validation.captcha_captcha'),
        ];
    }
}
