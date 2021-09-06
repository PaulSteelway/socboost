<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestSupport
 * @package App\Http\Requests
 *
 * @property string email
 * @property string text
 * @property string captcha
 */
class RequestSupport extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'   => 'required|max:255|email',
            'text'    => 'required|min:10',
            // 'captcha' => 'required|captcha',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'   => trans('main.emails.request.email_required'),
            'email.max'        => trans('main.emails.request.email_max'),
            'email.email'      => trans('main.emails.request.email_email'),

            'text.required'    => trans('main.emails.request.text_required'),
            'text.min'         => trans('main.emails.request.text_min'),

            // 'captcha.required' => trans('validation.captcha_required'),
            // 'captcha.captcha'  => trans('validation.captcha_captcha'),
        ];
    }
}
